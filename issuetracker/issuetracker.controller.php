<?php
    /**
     * @class  issuetrackerController
     * @author NHN (developers@xpressengine.com)
     * @brief  issuetracker 모듈의 Controller class
     **/

    class issuetrackerController extends issuetracker {

        /**
         * @brief 초기화
         **/
        function init() {
        }


        function procIssuetrackerInsertIssue() {
            // 권한 체크
            if(!$this->grant->ticket_write) return new Object(-1, 'msg_not_permitted');

            // 글작성시 필요한 변수를 세팅
            $obj = Context::getRequestVars();
            $obj->module_srl = $this->module_srl;

            if(!$obj->title) $obj->title = cut_str(strip_tags($obj->content),20,'...');

            // 관리자가 아니라면 게시글 색상/굵기 제거
            if(!$this->grant->manager) {
                unset($obj->title_color);
                unset($obj->title_bold);
            }
            // 커미터가 아니라면 마일스톤(계획), 우선순위, 소유자 설정 제거
            // (이슈 상태는 여기서 건드릴수없음 / 종류, 컴포넌트, 패키지 설정은 ticket_write권한이면 가능)
            if(!$this->grant->commiter) {
                unset($obj->assignee_srl);
                unset($obj->milestone_srl);
                unset($obj->priority_srl);
            }
            if($obj->release_srl)
            {
                $obj->occured_version_srl = $obj->release_srl;
            }

            if($obj->occured_version_srl == 0)
            {
                unset($obj->occured_version_srl);
            }

            // document module의 model 객체 생성
            $oDocumentModel = &getModel('document');

            // document module의 controller 객체 생성
            $oDocumentController = &getController('document');

            // 이미 존재하는 글인지 체크
            $oDocument = $oDocumentModel->getDocument($obj->document_srl, $this->grant->manager);

            // 이미 존재하는 경우 수정
            if($oDocument->isExists() && $oDocument->document_srl == $obj->document_srl) {
                $output = $oDocumentController->updateDocument($oDocument, $obj);
                $msg_code = 'success_updated';

                if(!$output->toBool()) return $output;

            // 그렇지 않으면 신규 등록
            } else {

				if($obj->assignee_srl) {
					// assignee name
					$oMemberModel = &getModel('member');
					$member_info = $oMemberModel->getMemberInfoByMemberSrl($obj->assignee_srl);
					$obj->assignee_name = $member_info->nick_name;
				}

                // transaction start
                $oDB = &DB::getInstance();
                $oDB->begin();
                $output = executeQuery("issuetracker.insertIssue", $obj);
                if(!$output->toBool()) {
                    $oDB->rollback();
                    return $output;
                }

                $output = $oDocumentController->insertDocument($obj);
                $msg_code = 'success_registed';
                $obj->document_srl = $output->get('document_srl');

                if(!$output->toBool()) {
                    $oDB->rollback();
                    return $output;
                }

                $oDB->commit();

                // 문제가 없고 모듈 설정에 관리자 메일이 등록되어 있으면 메일 발송
                if($output->toBool() && $this->module_info->admin_mail) {
                    $oMail = new Mail();
                    $oMail->setTitle($obj->title);
                    $oMail->setContent( sprintf("From : <a href=\"%s\">%s</a><br/>\r\n%s", getFullUrl('','document_srl',$obj->document_srl), getFullUrl('','document_srl',$obj->document_srl), $obj->content));
                    $oMail->setSender($obj->user_name, $obj->email_address);

                    $target_mail = explode(',',$this->module_info->admin_mail);
                    for($i=0;$i<count($target_mail);$i++) {
                        $email_address = trim($target_mail[$i]);
                        if(!$email_address) continue;
                        $oMail->setReceiptor($email_address, $email_address);
                        $oMail->send();
                    }
                }
            }

            // 오류 발생시 멈춤
            if(!$output->toBool()) return $output;

            // 결과를 리턴
            $this->add('mid', Context::get('mid'));
            $this->add('document_srl', $output->get('document_srl'));

            // 성공 메세지 등록
            $this->setMessage($msg_code);
        }

        function procIssuetrackerDeleteIssue() {
            // 문서 번호 확인
            $document_srl = Context::get('document_srl');

            // 문서 번호가 없다면 오류 발생
            if(!$document_srl) return $this->doError('msg_invalid_document');

            // document module model 객체 생성
            $oDocumentController = &getController('document');

            // 삭제 시도
            $output = $oDocumentController->deleteDocument($document_srl, $this->grant->manager);
            if(!$output->toBool()) return $output;

            // 이슈 삭제

            // 성공 메세지 등록
            $this->add('mid', Context::get('mid'));
            $this->add('page', $output->get('page'));
            $this->setMessage('success_deleted');
        }

        function triggerDeleteDocument(&$obj)
        {
            $args->target_srl = $obj->document_srl;
            if(!$args->target_srl) return new Object();
            $output = executeQuery('issuetracker.deleteIssue', $args);
			return $output;
        }

		function triggerDeleteComment(&$obj)
		{
			if(!$obj->comment_srl) return new Object();
			$output = executeQuery("issuetracker.deleteHistories", $obj);
			return $output;
		}

        function triggerMoveDocumentModule(&$obj)
        {
            $args->module_srl = $obj->module_srl;
            $args->document_srls = $obj->document_srls;
            $args->milestone_srl = 0;
            $args->type_srl = 0;
            $args->priority_srl = 0;
            $args->component_srl = 0;
            $args->resolution_srl = 0;
            $output = executeQuery('issuetracker.updateIssueModule', $args);
            if(!$output->toBool()) return $output;

			$output = executeQueryArray("issuetracker.getComments", $args);
			if(!$output->data) return new Object();

			$comment_srls = array();	
			foreach($output->data as $comment)
			{
				$comment_srls[] = $comment->comment_srl;
			}

            $args = null;
            $args->module_srl = $obj->module_srl;
            $args->comment_srls = implode(",", $comment_srls);
            $output = executeQuery('issuetracker.updateIssueHistoryModule', $args);
            return $output;
        }

        function insertHistory($target_srl, $objs, $module_srl, $grant)
        {
            $oIssuetrackerModel = &getModel('issuetracker');
            $oIssue = $oIssuetrackerModel->getIssue($target_srl);
            if(!$oIssue->isExists()) return new Object(-1,'msg_not_founded');
            $objs->comment_srl = Context::get('history_srl');

            // trigger 호출 (before)
            $output = ModuleHandler::triggerCall('issuetracker.insertHistory', 'before', $objs);
            if(!$output->toBool()) return $output;

            $logged_info = Context::get('logged_info');

            $args = null;

            // 글작성시 필요한 변수를 세팅
            $args->comment_srl = $objs->comment_srl?$objs->comment_srl:getNextSequence();
			
            $args->uploaded_count = $objs->uploaded_count;
            $args->target_srl = $target_srl;
            $args->document_srl = $target_srl;
            $args->content = Context::get('content');
            if($logged_info->member_srl) {
                $args->member_srl = $logged_info->member_srl;
                $args->nick_name = $logged_info->nick_name;
            } else {
                $args->nick_name = Context::get('nick_name');
                $args->password = Context::get('password');
            }

            // 커미터일 경우 각종 상태 변경값을 받아서 이슈의 상태를 변경하고 히스토리 생성
            if($grant) {
                $milestone_srl = $objs->milestone_srl;
                $priority_srl = $objs->priority_srl;
                $type_srl = $objs->type_srl;
                $component_srl = $objs->component_srl;
                $package_srl = $objs->package_srl;
                $occured_version_srl = $objs->occured_version_srl;
                $action = $objs->action;
                $status = $objs->status;
                $assignee_srl = $objs->assignee_srl;

                $change_args = null;

                if($milestone_srl && $milestone_srl != $oIssue->get('milestone_srl')) {
					$c_args = null;
					$c_args->type="m";
					$c_args->before = $oIssue->get('milestone_srl');
					$c_args->after = $milestone_srl;
					$c_args->comment_srl = $args->comment_srl;
					$c_args->module_srl = $module_srl;
					$output2 = executeQuery("issuetracker.insertHistoryChange", $c_args);
					$change_args->milestone_srl = $milestone_srl;
                }

                if($priority_srl && $priority_srl != $oIssue->get('priority_srl')) {
					$c_args = null;
					$c_args->type="p";
					$c_args->before = $oIssue->get('priority_srl');
					$c_args->after = $priority_srl;
					$c_args->comment_srl = $args->comment_srl;
					$c_args->module_srl = $module_srl;
					$output2 = executeQuery("issuetracker.insertHistoryChange", $c_args);
					$change_args->priority_srl = $priority_srl;
				}

                if($type_srl && $type_srl != $oIssue->get('type_srl')) {
					$c_args = null;
					$c_args->type="t";
					$c_args->before = $oIssue->get('type_srl');
					$c_args->after = $type_srl;
					$c_args->comment_srl = $args->comment_srl;
					$c_args->module_srl = $module_srl;
					$output2 = executeQuery("issuetracker.insertHistoryChange", $c_args);
					$change_args->type_srl = $type_srl;
                }

                if($component_srl && $component_srl != $oIssue->get('component_srl')) {
					$c_args = null;
					$c_args->type="c";
					$c_args->before = $oIssue->get('component_srl');
					$c_args->after = $component_srl;
					$c_args->comment_srl = $args->comment_srl;
					$c_args->module_srl = $module_srl;
					$output2 = executeQuery("issuetracker.insertHistoryChange", $c_args);
					$change_args->component_srl = $component_srl;
                }

                if($package_srl && $package_srl != $oIssue->get('package_srl')) {
					$c_args = null;
					$c_args->type="r";
					$c_args->before = $oIssue->get('package_srl');
					$c_args->after = $package_srl;
					$c_args->comment_srl = $args->comment_srl;
					$c_args->module_srl = $module_srl;
					$output2 = executeQuery("issuetracker.insertHistoryChange", $c_args);
					$change_args->package_srl = $package_srl;
                }

                if($occured_version_srl && $occured_version_srl != $oIssue->get('occured_version_srl')) {
					$c_args = null;
					$c_args->type="v";
					$c_args->before = $oIssue->get('occured_version_srl');
					$c_args->after = $occured_version_srl;
					$c_args->comment_srl = $args->comment_srl;
					$c_args->module_srl = $module_srl;
					$output2 = executeQuery("issuetracker.insertHistoryChange", $c_args);
					$change_args->occured_version_srl = $occured_version_srl;
				}

				$status_rev_list = array();
				foreach($this->status_list as $key=>$value)
				{
					$status_rev_list[$value] = $key;
				}

                switch($action) {
                    case 'resolve' :
						if($oIssue->get('status') != $status) {
							$c_args = null;
							$c_args->type="s";
							$c_args->before = $status_rev_list[$oIssue->get('status')];
							$c_args->after = $status_rev_list[$status]; 
							$c_args->comment_srl = $args->comment_srl;
							$c_args->module_srl = $module_srl;
							$output2 = executeQuery("issuetracker.insertHistoryChange", $c_args);
							$change_args->status = $status;
						}
						break;
					case 'accept':
						$assignee_srl = $logged_info->member_srl;
						$assignee_name = $logged_info->nick_name;
                    case 'reassign' :
						$status = "assign";
						if($oIssue->get('status')!='assign') {
							$c_args = null;
							$c_args->type="s";
							$c_args->before = $status_rev_list[$oIssue->get('status')];
							$c_args->after = $status_rev_list[$status]; 
							$c_args->comment_srl = $args->comment_srl;
							$c_args->module_srl = $module_srl;
							$output2 = executeQuery("issuetracker.insertHistoryChange", $c_args);
							$change_args->status = "assign";
						}
						if($oIssue->get('assignee_srl') != $assignee_srl)
						{
							$c_args = null;
							$c_args->type="a";
							$c_args->before = $oIssue->get('assignee_srl'); 
							$c_args->after = $assignee_srl;
							$c_args->comment_srl = $args->comment_srl;
							$c_args->module_srl = $module_srl;
							$output2 = executeQuery("issuetracker.insertHistoryChange", $c_args);
							if(!$assignee_name)
							{
								$oMemberModel = &getModel('member');
								$member_info = $oMemberModel->getMemberInfoByMemberSrl($assignee_srl);
								$assignee_name = $member_info->nick_name;
							}
							$change_args->assignee_srl = $assignee_srl;
							$change_args->assignee_name = $assignee_name;
						}
						break;
				}
                if($change_args!==null) {
                    // 이슈 상태 변경시 보고자에게 쪽지 발송
                    if($oIssue->get('member_srl') && $oIssue->useNotify()) {
                        // 현재 로그인한 사용자와 글을 쓴 사용자를 비교하여 동일하지 않으면 진행
                        if($logged_info->member_srl != $oIssue->get('member_srl')) {
                            // 변수 정리
                            $title = '['.Context::getLang('cmd_resolve_as').'-'.$status_lang[$change_args->status].'] '.$oIssue->getTitleText();
                            $content = sprintf('%s<br /><br />from : <a href="%s" onclick="window.open(this.href);return false;">%s</a>', nl2br($args->content), $oIssue->getPermanentUrl(), $oIssue->getPermanentUrl());
                            $receiver_srl = $oIssue->get('member_srl');
                            $sender_member_srl = $logged_info->member_srl;

                            // 쪽지 발송
                            $oCommunicationController = &getController('communication');
                            $oCommunicationController->sendMessage($sender_member_srl, $receiver_srl, $title, $content, false);
                        }
                    }

                    $change_args->target_srl = $target_srl;
                    $target_column = array('assignee_srl','assignee_name','milestone_srl','type_srl','priority_srl','component_srl','occured_version_srl','resolution_srl','status');
                    $changed = false;
                    foreach($target_column as $target) {
                        if(isset($change_args->{$target})) {
                            $changed = true;
                            break;
                        }
                    }
                    if($changed) {
                        $output = executeQueryArray('issuetracker.updateIssue', $change_args);
                        if(!$output->toBool()) return $output;
                        $args->history = serialize($history);
                    }
                }
            }

            $args->issues_history_srl = $args->comment_srl; 
            $args->module_srl = $module_srl;

			$oCommentController =& getController('comment');
			$output = $oCommentController->insertComment($args);
            if(!$output->toBool()) return $output;

			if($output->toBool() && $this->module_info->admin_mail) {

				$comment_srl = $args->comment_srl;
				$oCommentModel = &getModel('comment');
				$oComment = $oCommentModel->getComment($comment_srl);

				$oDocumentModel = &getModel('document');
				$oDocument = $oDocumentModel->getDocument($target_srl);
				$document_srl = $oDocument->document_srl;

				$oMail = new Mail();
				$title = $action ? sprintf('[%s] ', $action) : '';
				$title .= $oDocument->getTitleText();

				$oMail->setTitle($title);
				$oMail->setContent( sprintf("From : <a href=\"%s#comment_%d\">%s#comment_%d</a><br/>\r\n%s", getFullUrl('','document_srl',$document_srl),$comment_srl, getFullUrl('','document_srl',$document_srl), $comment_srl, $oComment->getSummary(200)));
				$oMail->setSender($oComment->get('user_name'), $oComment->get('email_address'));

				$target_mail = explode(',',$this->module_info->admin_mail);
				for($i=0;$i<count($target_mail);$i++) {
					$email_address = trim($target_mail[$i]);
					if(!$email_address) continue;
					$oMail->setReceiptor($email_address, $email_address);
					$oMail->send();
				}
			}

            // trigger 호출 (after)
            $output = ModuleHandler::triggerCall('issuetracker.insertHistory', 'after', $args);
            if(!$output->toBool()) return $output;

			return new Object();
        }

        function procIssuetrackerInsertHistory() {
            // 권한 체크
            if(!$this->grant->ticket_write && !$this->grant->commiter) return new Object(-1, 'msg_not_permitted');

            // 원 이슈를 가져옴
            $target_srl = Context::get('target_srl');
            $args = Context::gets('milestone_srl', 'priority_srl', 'type_srl', 'component_srl', 'package_srl', 'occured_version_srl', 'action', 'status', 'assignee_srl','release_srl');
            if($args->release_srl)
            {
                $args->occured_version_srl = $args->release_srl;
            }
            $output = $this->insertHistory($target_srl, $args, $this->module_srl, $this->grant->commiter);


            if(!$output->toBool())
            {
                return $output;
            }


            $this->add('document_srl', $target_srl);
            $this->add('mid', $this->module_info->mid);
        }

        function procIssuetrackerVerificationPassword() {
            // 비밀번호와 문서 번호를 받음
            $password = Context::get('password');
            $document_srl = Context::get('document_srl');
            $comment_srl = Context::get('comment_srl');

            $oMemberModel = &getModel('member');

            // comment_srl이 있을 경우 댓글이 대상
            if($comment_srl) {
                // 문서번호에 해당하는 글이 있는지 확인
                $oCommentModel = &getModel('comment');
                $oComment = $oCommentModel->getComment($comment_srl);
                if(!$oComment->isExists()) return new Object(-1, 'msg_invalid_request');

                // 문서의 비밀번호와 입력한 비밀번호의 비교
                if(!$oMemberModel->isValidPassword($oComment->get('password'),$password)) return new Object(-1, 'msg_invalid_password');

                $oComment->setGrant();
            } else {
                // 문서번호에 해당하는 글이 있는지 확인
                $oDocumentModel = &getModel('document');
                $oDocument = $oDocumentModel->getDocument($document_srl);
                if(!$oDocument->isExists()) return new Object(-1, 'msg_invalid_request');

                // 문서의 비밀번호와 입력한 비밀번호의 비교
                if(!$oMemberModel->isValidPassword($oDocument->get('password'),$password)) return new Object(-1, 'msg_invalid_password');

                $oDocument->setGrant();
            }
        }

        function procIssuetrackerDeleteTrackback() {
            $trackback_srl = Context::get('trackback_srl');

            // trackback module의 controller 객체 생성
            $oTrackbackController = &getController('trackback');
            $output = $oTrackbackController->deleteTrackback($trackback_srl, $this->grant->manager);
            if(!$output->toBool()) return $output;

            $this->add('mid', Context::get('mid'));
            $this->add('document_srl', $output->get('document_srl'));
            $this->setMessage('success_deleted');
        }

        function syncChangeset($module_info)
        {
            if(!$module_info->svn_url || !$module_info->svn_cmd) return;

            $lock_file = sprintf('%sfiles/cache/svn/%d.lock', _XE_PATH_,$module_info->module_srl);
            if(file_exists($lock_file) && filemtime($lock_file)>time()-60*10) return;
            FileHandler::writeFile($lock_file,' ');

            require_once($this->module_path.'classes/svn.class.php');
            $oSvn = new Svn($module_info->svn_url, $module_info->svn_cmd, $module_info->svn_userid, $module_info->svn_passwd);
            $oModel = &getModel('issuetracker');
            $status = $oSvn->getStatus();
            if(!$status || !$status->revision) return;
            $latestRevision = $oModel->getLatestRevision($module_info->module_srl);

            $oController = &getController('issuetracker');
			$member_info_array = array();
			$oMemberModel =& getModel('member');
            while($latestRevision < $status->revision)
            {
                $gap = $status->revision-$latestRevision;
                if($gap > 500) $gap = 500;
                $logs = $oSvn->getLog("/", $latestRevision+1, $status->revision, false, $gap, false);
                if(!$logs || !count($logs)) return;
                if(count($logs)) {
                    foreach($logs as $log)
                    {
                        $obj = null;
                        $obj->revision = $log->revision;
                        $obj->author = $log->author;
                        $obj->date = date("YmdHis", strtotime($log->date));
                        $obj->message = trim($log->msg);
                        $obj->module_srl = $module_info->module_srl;
						if(!isset($member_info_array[$obj->author])) {
							$member_info_array[$obj->author] = $oMemberModel->getMemberInfoByUserID($obj->author);
						}
						if($member_info_array[$obj->author]) $obj->member_srl = $member_info_array[$obj->author]->member_srl;
                        $output = executeQuery("issuetracker.insertChangeset", $obj);
						if($output->toBool())
						{
							$output = ModuleHandler::triggerCall('issuetracker.insertChangeset', 'after', $obj);
						}
                    }
                }
                $latestRevision = $oModel->getLatestRevision($module_info->module_srl);
            }
            FileHandler::removeFile($lock_file);
        }
        /**
         * @brief 이슈트래커 릴리즈 다운로드시 다운로드 정보를 테이블에 기록하는 trigger
         **/
        function triggerDownloadCounter(&$obj){
           
            $oModuleModel = &getModel('module');
            $config = $oModuleModel->getModuleConfig('issuetracker');
            if($config->download_counter == 'Y'){
                         
                $args->file_srl = Context::get('file_srl');
                $args->sid = Context::get('sid');
                $result = executeQuery('issuetracker.getIssueFileCheck', $args);
                $data = $result->data;
            
                if($data) { //1:1매칭이므로 1row
                    $logged_info = Context::get('logged_info');
                    if($logged_info){
                        $args->member_srl = $logged_info->member_srl;
                    }
                    $args->release_srl = $data->upload_target_srl;
                    $input  = executeQuery('issuetracker.insertIssueDownloadHistory', $args);                
                }                         
            }
            return new Object();
        }

        /**
         * @brief 아이디 클릭시 나타나는 팝업메뉴에 "작성글 보기" 메뉴를 추가하는 trigger - board 모듈과 동일
         **/
        function triggerMemberMenu() {
            $member_srl = Context::get('target_srl');
            $mid = Context::get('cur_mid');

            if(!$member_srl || !$mid) return new Object();

            $logged_info = Context::get('logged_info');

            // 호출된 모듈의 정보 구함
            $oModuleModel = &getModel('module');
            $cur_module_info = $oModuleModel->getModuleInfoByMid($mid);

            if($cur_module_info->module != 'issuetracker') return new Object();

            // 자신의 아이디를 클릭한 경우
            if($member_srl == $logged_info->member_srl) {
                $member_info = $logged_info;
            } else {
                $oMemberModel = &getModel('member');
                $member_info = $oMemberModel->getMemberInfoByMemberSrl($member_srl);
            }

            if(!$member_info->user_id) return new Object();

            // 아이디로 검색기능 추가
            $url = getUrl('','mid',$mid,'act','dispIssuetrackerViewIssue','status[0]','new','status[1]','reviewing','status[2]','assign','status[3]','resolve','status[4]','reopen','status[5]','postponed','status[6]','duplicated','status[7]','invalid','search_target','user_id','search_keyword',$member_info->user_id);
            $oMemberController = &getController('member');
            $oMemberController->addMemberPopupMenu($url, 'cmd_view_own_document', './modules/member/tpl/images/icon_view_written.gif');

            return new Object();
        }
    }
?>
