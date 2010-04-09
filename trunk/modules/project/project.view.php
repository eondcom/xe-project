<?php
    /**
     * @class  projectView
     * @author zero (zero@nzeo.com)
     * @brief  project 모듈의 view class
     **/

    class projectView extends project {

        var $site_module_info = null;
        var $site_srl = 0;
        var $project_info = null;
		var $my_projects = null;
		var $sort_order = null;

        /**
         * @brief 초기화 
         **/
        function init() {
            global $lang;

            $oModuleModel = &getModel('module');
            $oProjectModel = &getModel('project');

            // 가상 사이트 정보 설정
            $this->site_module_info = Context::get('site_module_info');
            $this->site_srl = $this->site_module_info->site_srl;

            // 프로젝트 정보를 추출하여 세팅
            $this->project_info = $oProjectModel->getProjectInfo($this->site_srl);
            Context::set('project_info', $this->project_info);
			if($this->site_srl)
			{
				$project_site_config = $oProjectModel->getConfig($this->site_srl);
				Context::set('project_site_config', $project_site_config);
			}

            // 메인페이지가 아니고 Project Action인 경우 사이트 관리 권한이 없으면 접근 금지
            if(!in_array($this->act, array('dispProjectMyProjectActivity', 'dispProjectMyActivity', 'dispProjectContributors', 'dispProjectPlan', 'dispProjectMyPlan', 'dispProjectSummary', 'dispProjectMySummary', 'dispProjectDownloadSearch', 'dispProjectSearch', 'dispProjectIndex','dispProjectMember','dispProjectNews','dispProjectPostNews','dispProjectCreateProject','dispProjectOffer','dispProjectPostOffer','dispProjectOffer','dispProjectAccountManage')) && strpos($this->act,'Project')!==false) {
                // 현재 접속 권한 체크하여 사이트 관리자가 아니면 접근 금지
                $logged_info = Context::get('logged_info');
                if(!Context::get('is_logged') || !$oModuleModel->isSiteAdmin($logged_info)) return $this->stop('msg_not_permitted');

                // site_module_info값으로 프로젝트의 정보를 구함
                if(!$this->site_srl) return $this->stop('msg_invalid_request');

                // 프로젝트 관리 기능이므로 tpl 디렉토리를 템플릿 디렉토리로 설정
                $template_path = sprintf("%stpl",$this->module_path);
                $this->setTemplatePath($template_path);

                // 모듈 번호가 있으면 해동 모듈의 정보를 구해와서 세팅
                $module_srl = Context::get('module_srl');
                if($module_srl) {
                    $module_info = $oModuleModel->getModuleInfoByModuleSrl($module_srl);
                    if(!$module_info || $module_info->site_srl != $this->site_srl) return new Object(-1,'msg_invalid_request');
                    $this->module_info = $module_info;
                    Context::set('module_info', $module_info);
                }
            } else {
                // 템플릿 경로 지정
                $template_path = sprintf("%sskins/%s/",$this->module_path, $this->module_info->skin);
                if(!is_dir($template_path)||!$this->module_info->skin) {
                    $this->module_info->skin = 'xe_project';
                    $template_path = sprintf("%sskins/%s/",$this->module_path, $this->module_info->skin);
                }
                $this->setTemplatePath($template_path);
            }

			if(Context::get('is_logged')) {
				$logged_info = Context::get('logged_info');
				$margs->member_srl = $logged_info->member_srl;
				$output = executeQueryArray('project.getMyProjects', $margs);
				if(!$output->data) $output->data = array();
				$this->my_projects = $output->data;
				Context::set('my_projects', $output->data);
			}
        }

		function dispProjectMyActivity()
		{
            $logged_info = Context::get('logged_info');
            if(!$logged_info)
            {
				Context::set('act','dispProjectSummary');
                return $this->dispProjectSummary();
            }

			$oModel =& getModel('project');
			$page = Context::get('page');
			if(!$page)
			{
				$page = 1;
				Context::set('page',$page);
			}
			$output = $oModel->getNewItems($page, null, $logged_info->member_srl);
			if(count($output->sites) > 0)
			{
				Context::set('projects', $oModel->getProjects(implode(",", $output->sites)));
			}

			Context::set('modules', $output->modules);
            Context::set('page_navigation', $output->page_navigation);
			Context::set('activities', $output->data);


			$this->setTemplateFile("myactivity.html");
		}

		function dispProjectMyProjectActivity()
		{
			$this->dispProjectMyActivity();
			$this->setTemplateFile("myprojectactivity.html");
		}

        /**
         * @brief 프로젝트 index
         * site_srl 이 있으면 개별 프로젝트, 아니면 프로젝트 메인을 출력
         **/
        function dispProjectIndex() {
            $site_module_info = Context::get('site_module_info');
            if(!$site_module_info->site_srl) $this->dispProjectMain();
            else $this->dispProject();
        }

        /**
         * @brief 프로젝트 메인
         **/
        function dispProjectMain() {
            $logged_info = Context::get('logged_info');
            if(!$logged_info)
            {
				Context::set('act','dispProjectSummary');
                return $this->dispProjectSummary();
            }
            else
            {
				Context::set('act','dispProjectMySummary');
                return $this->dispProjectMySummary();
            }
        }

		function dispProjectSearch() {
            $oProjectModel = &getModel('project');
            Context::set('directories', $oProjectModel->getProjectAllDirectories());

            $page = Context::get('page');
            if(!$page) {
                $page = 1;
                Context::set('page', $page);
            }
			$sort_order = Context::get('sort_order');
			if(!$sort_order) {
				$sort_order = 'point';
				Context::set('sort_order', $sort_order);
			}

			$search_keyword = Context::get('search_keyword');
			$project_dir_srl = Context::get('project_dir_srl');
            $output = $oProjectModel->getProjectListInSummary($page, $sort_order, 10, $search_keyword, $project_dir_srl);
            Context::set('page_navigation', $output->page_navigation);
			Context::set('project_list', $output->data);


			$this->setTemplateFile('project_search');
		}

		function dispProjectDownloadSearch() {
            $oProjectModel = &getModel('project');
            Context::set('directories', $oProjectModel->getProjectAllDirectories());

            $page = Context::get('page');
            if(!$page) {
                $page = 1;
                Context::set('page', $page);
            }
			$sort_order = Context::get('sort_order');
			if(!$sort_order) {
				$sort_order = 'download_count';
				Context::set('sort_order', $sort_order);
			}
			$output = $oProjectModel->getReleases($sort_order, $page);
            Context::set('page_navigation', $output->page_navigation);
			Context::set('project_list', $output->data);


			$this->setTemplateFile('download_search');
		}

		function dispProjectMyPlan() {
			$site_srls = array();
			foreach($this->my_projects as $val)
			{
				$site_srls[] = $val->site_srl;
			}
			$this->dispProjectPlan(implode(",", $site_srls));
		}

		function dispProjectPlan($site_srls = null) {
			$startdate = Context::get('startdate');
			$enddate = Context::get('enddate');
			$oModel =& getModel('project');
			if($startdate)
			{
				$aftermore = false;
				$list = $oModel->getIssueMilestones($startdate, null, "asc", 10, $aftermore, $site_srls);
				Context::set('milestones', $list);
				Context::set('aftermore', $aftermore);
				Context::set('beforemore', true);
			}
			else if($enddate)
			{
				$beforemore = false;
				$enddate = date("Ymd", ztime($enddate) - 24*60*60);
				$list = $oModel->getIssueMilestones(null, $enddate, "desc", 10, $beforemore, $site_srls);
				Context::set('milestones', $list);
				Context::set('aftermore', true);
				Context::set('beforemore', $beforemore);
			}
			else
			{
				$startdate = date("Ymd");
				$beforemort = false;
				$aftermore = false;
				$list1 = $oModel->getIssueMilestones($startdate, null, "asc", 5, $aftermore, $site_srls);
				$list2 = $oModel->getIssueMilestones(null, $startdate, "desc", 5, $beforemore, $site_srls);
				Context::set('aftermore', $aftermore);
				Context::set('beforemore', $beforemore);
				$list = array_merge($list2, $list1); 
				Context::set('milestones', $list);
			}
			
			$this->setTemplateFile('project_plan');
		}

		function dispPopularDownloads() {
			$args->today = date("Ymd");
			$args->weekago = date("Ymd", ztime(date("Ymd")) - 24*60*60*7);
			$output = executeQueryArray("project.getPopularDownloads", $args);
			if(!$output->data) return;
			$release_srls = array();
			foreach($output->data as $data)
			{
				$release_srls[] = $data->release_srl;
			}
			$args->release_srls = implode(",", $release_srls);
			$output2 = executeQueryArray("project.getReleases", $args);
			$releases = array();
            if(!$output2->data) return;
			foreach($output2->data as $data)
			{
				$releases[$data->release_srl] = $data;
			}

			foreach($output->data as $key=>$data)
			{
				$output->data[$key]->item =  $releases[$data->release_srl]; 
			}
			Context::set('popular_downloads', $output->data);
		}

        function dispProjectSummary() {
            $oProjectModel =& getModel('project');
            $page = Context::get('page');
            if(!$page) {
                $page = 1;
                Context::set('page', $page);
            }
			$sort_order = Context::get('sort_order');
			if(!$sort_order) {
				$sort_order = 'point';
				Context::set('sort_order', $sort_order);
			}

            $output = $oProjectModel->getProjectListInSummary($page, $sort_order);
            Context::set('page_navigation', $output->page_navigation);
			Context::set('project_list', $output->data);

			$this->dispPopularDownloads();

			if($page == 1 && $sort_order == "regdate")
			{
				$output2 = $output;
			}
			else
			{
				$output2 = $oProjectModel->getProjectList(1, null, 0, 5, "regdate");
			}
			Context::set('recent_project_list', $output2->data);

            $page = Context::get('news_page');
            if(!$page) {
                $page = 1;
                Context::set('news_page', $page);
            }

			
			$output = $oProjectModel->getNewItems($page);
			if(count($output->sites) > 0)
			{
				Context::set('projects', $oProjectModel->getProjects(implode(",", $output->sites)));
			}

			Context::set('modules', $output->modules);
            Context::set('news_page_navigation', $output->page_navigation);
			Context::set('news_list', $output->data);
            
            $this->setTemplateFile('project_summary');
        }

		function cmp_target($a, $b)
		{
			if( $a->{$this->sort_order} == $b->{$this->sort_order} ) return 0;
			return ( $a->{$this->sort_order} > $b->{$this->sort_order} ) ? -1 : 1;
		}

        function dispProjectMySummary() {
            $oProjectModel =& getModel('project');
			$logged_info = Context::get('logged_info');
			if(!$logged_info) return $this->dispProjectSummary();
            $args->member_srl = $logged_info->member_srl;
            $page = Context::get('page');
            if(!$page) {
                $page = 1;
                Context::set('page', $page);
            }

			$sort_order = Context::get('sort_order');
			if(!$sort_order)
			{
				$sort_order = "point";
				Context::set('sort_order',$sort_order);
			}
			$this->sort_order = $sort_order;

			$output = executeQueryArray("project.getActivityPoints", $args);
			if(!$output->data) $output->data = array();
			$apoints = array();
			foreach($output->data as $data)
			{
				$apoints[$data->site_srl] = $data->point;
			}


			$site_srls = array();
			foreach($this->my_projects as $key=>$project)
			{
				$this->my_projects[$key]->point = $apoints[$project->site_srl]?$apoints[$project->site_srl]:0;
				$site_srls[] = $project->site_srl;
			}
			$args->site_srl = implode(",", $site_srls);
			$output = executeQueryArray("project.getProjectMemberCount", $args);
			if(!$output->data) $output->data = array();
			$member_counts = array();
			foreach($output->data as $data)
			{
				$member_counts[$data->site_srl] = $data->count;
			}

			foreach($this->my_projects as $key=>$project)
			{
				$this->my_projects[$key]->member_count = $member_counts[$project->site_srl]?$member_counts[$project->site_srl]:0;
			}

			usort( $this->my_projects, array( $this, "cmp_target" ) );
			$list_count = 5;
			$projects = array_slice($this->my_projects, ($page-1)*$list_count, $list_count);
			
            $output = $oProjectModel->getProjectList($page, null, 0, 5, 'rank', $args->member_srl);
			$total_count = count($this->my_projects);
            Context::set('page_navigation', new PageHandler($total_count, ($total_count+$list_count-1)/$list_count, $page));
			Context::set('project_list', $projects);

			$output2 = $oProjectModel->getProjectList(1, null, 0, 5, "regdate");
			Context::set('recent_project_list', $output2->data);

            $page = Context::get('news_page');
            if(!$page) {
                $page = 1;
                Context::set('news_page', $page);
            }

			$output = $oProjectModel->getNewItems($page, implode(",", $site_srls));
			if(count($output->sites) > 0)
			{
				Context::set('projects', $oProjectModel->getProjects(implode(",", $output->sites)));
			}

			Context::set('modules', $output->modules);
            Context::set('news_page_navigation', $output->page_navigation);
			Context::set('news_list', $output->data);

			$this->dispPopularDownloads();

            $this->setTemplateFile('mysummary');
        }

        function oldProjectMain() { 
            $oProjectAdminModel = &getAdminModel('project');
            $oProjectModel = &getModel('project');
            $oModuleModel = &getModel('module');
            $oDocumentModel = &getModel('document');
            $oCommentModel = &getModel('comment');
            $oIssueTrackerModel = &getModel('issuetracker');

			// 최고 포인트 및 프로젝트 수/배포 수/ 뉴스 수/ 구인 수를 구함
			$output = executeQuery('project.getMaxPoint');
			$max_point = $output->data->point;
			if(!$max_point) $max_point = 1;
			Context::set('max_point', $max_point);

			$output = executeQuery('project.getProjectCount');
			$total->project = $output->data->count;
			$output = executeQuery('project.getNewsCount');
			$total->news = $output->data->count;
			$output = executeQuery('project.getOfferCount');
			$total->offer = $output->data->count;
			$output = executeQuery('project.getReleaseCount');
			$total->release= $output->data->count;
            Context::set('total',$total);

            $type = Context::get('type');
            $search_keyword = Context::get('search_keyword');
            $project_dir_srl = Context::get('dir_srl');
			if($project_dir_srl && !$type) Context::set('type', $type = 'project');
            switch($type) {
                // 뉴스
                case 'news' :
                        $page = Context::get('page');
                        $args->page = $page;
                        $args->search_keyword = $search_keyword;
                        if($project_dir_srl) {
                            $args->project_dir_srl = $project_dir_srl;
                            $output = executeQuery('project.getDirectoryNewsList', $args);
                        } else {
                            $output = executeQuery('project.getNewsList', $args);
                        }
						Context::set('total_count', $output->total_count);
						Context::set('total_page', $output->total_page);
						Context::set('page', $output->page);
						Context::set('item_list', $output->data);
						Context::set('page_navigation', $output->page_navigation);

                    break;

                // 구인
                case 'offer' :
                        $page = Context::get('page');
                        $args->page = $page;
                        $args->search_keyword = $search_keyword;
                        if($project_dir_srl) {
                            $args->project_dir_srl = $project_dir_srl;
                            $output = executeQuery('project.getDirectoryOfferList', $args);
                        } else {
                            $output = executeQuery('project.getOfferList', $args);
                        }
						Context::set('total_count', $output->total_count);
						Context::set('total_page', $output->total_page);
						Context::set('page', $output->page);
						Context::set('item_list', $output->data);
						Context::set('page_navigation', $output->page_navigation);

                    break;

                // 릴리즈
                case 'release' :
                        $page = Context::get('page');
                        $args->page = $page;
                        $args->search_keyword = $search_keyword;
                        if($project_dir_srl) {
                            $args->project_dir_srl = $project_dir_srl;
                            $output = executeQueryArray('project.getDirectoryReleaseList', $args);
                        } else {
                            $output = executeQueryArray('project.getReleaseList', $args);
                        }
                        if($output->data) {
                            $oFileModel = &getModel('file');
                            foreach($output->data as $key => $release) {
                                $files = $oFileModel->getFiles($release->release_srl);
                                $release->files = $files;
                                $output->data[$key] = $release;
                            }
                        }
						Context::set('total_count', $output->total_count);
						Context::set('total_page', $output->total_page);
						Context::set('page', $output->page);
						Context::set('item_list', $output->data);
						Context::set('page_navigation', $output->page_navigation);

                    break;

                // 프로젝트 목록을 구함
                case 'project':
                        $page = Context::get('page');
                        $output = $oProjectAdminModel->getProjectList($page, $search_keyword, Context::get('dir_srl'), 20, Context::get('sort_index')!='list_order'?'rank':'list_order');
						$output->data = $this->setProjectInfo($output->data);
						Context::set('total_count', $output->total_count);
						Context::set('total_page', $output->total_page);
						Context::set('page', $output->page);
						Context::set('item_list', $output->data);
						Context::set('page_navigation', $output->page_navigation);

                    break;

				// 기본 정보
				default :
						// 뉴스 추출
						$n_args->list_count = 5;
						$output = executeQuery('project.getNewsList', $n_args);
						Context::set('news', $output->data);

						// 구인 추출
						$n_args->list_count = 5;
						$output = executeQuery('project.getOfferList', $n_args);
						Context::set('offer', $output->data);

						// 최신 글 추출
						$n_args->list_count = 5;
						$n_args->mid = "'wiki','forum'";
						$output = executeQueryArray('project.getNewestDocuments', $n_args);
						if($output->data) {
								foreach($output->data as $key => $attribute) {
										unset($oDocument);
										$oDocument = new documentItem();
										$oDocument->setAttribute($attribute);
                                        $oDocument->add('project_title', $attribute->project_title);
										$output->data[$key] = $oDocument;
								}
								Context::set('newest_documents', $output->data);
						}

						// 최신 댓글 추출
						$n_args->list_count = 5;
						$output = executeQueryArray('project.getNewestComments', $n_args);
						if(count($output->data)) {
								foreach($output->data as $key => $val) {
										unset($oComment);
										$oComment = new commentItem();
										$oComment->setAttribute($val); 
                                        $oComment->add('project_title', $val->project_title);
										$output->data[$key] = $oComment;
								}
								Context::set('newest_comments', $output->data);
						}

                        // release
						$r_args->list_count = 5;
                        $output = executeQueryArray('project.getNewestRelease', $r_args);
                        Context::set('releases', $output->data);

						// 상위 20개의 프로젝트 구함
						$top_args->sort_index = 'projects.point';
						$top_args->order_type = 'desc';
						$top_args->list_count = 20;
						$top_args->page = 1;
						$output = executeQueryArray('project.getProjectList',$top_args);
						Context::set('top_projects', $this->setProjectInfo($output->data));

						// 최신 5개의 프로젝트 구함
						$new_args->sort_index = 'projects.list_order';
						$new_args->order_type = 'asc';
						$new_args->list_count = 5;
						$new_args->page = 1;
						$output = executeQueryArray('project.getProjectList',$new_args);
						Context::set('new_projects', $this->setProjectInfo($output->data));
					break;
				}

				// 프로젝트 생성 권한 세팅
				if($oProjectModel->isCreationGranted()) Context::set('isEnableCreateProject', true);

				// RSS 세팅
				$project_info = $oModuleModel->getModuleConfig('project');
				if($project_info->use_rss == 'Y') Context::set('rss_url',getUrl('','mid',$this->module_info->mid,'act','rss'));

				// 나의 프로젝트 가져오기
				if(Context::get('is_logged')) {
				$logged_info = Context::get('logged_info');
				$margs->member_srl = $logged_info->member_srl;
				$output = executeQueryArray('project.getMyProjects', $margs);
				Context::set('my_projects', $output->data);
            }

            // 디렉토리 구해서 $category라는 전역 변수로 등록
            Context::set('category', $oProjectModel->getProjectAllDirectories());

            $this->setTemplateFile('index');
        }

		/**
		 * @brief project 목록을 넘겨 받아 배너/로고 이미지와 회원수를 세팅
		 */
		function setProjectInfo($list) {
			if(!$list || !count($list)) return array();
			foreach($list as $key => $val) {
				$logo_src = 'files/attach/project_logo/'.$val->site_srl.'.jpg';
				if(file_exists(_XE_PATH_.$logo_src)) $list[$key]->project_logo = $logo_src.'?rnd='.filemtime(_XE_PATH_.$logo_src);
				else $list[$key]->project_logo = $this->module_path.'tpl/img/projectLogo.gif';

				$banner_src = 'files/attach/project_banner/'.$val->site_srl.'.jpg';
				if(file_exists(_XE_PATH_.$banner_src)) $list[$key]->project_banner = $banner_src.'?rnd='.filemtime(_XE_PATH_.$banner_src);
				else $list[$key]->project_banner = $this->module_path.'tpl/img/projectBanner.gif';

				$site_srl_list[$val->site_srl] = $key;
			}
			if(count($site_srl_list)) {
				$c_args->site_srl = implode(',',array_keys($site_srl_list));
				$c_output = executeQueryArray('project.getProjectMemberCount', $c_args);
				if($c_output->data) {
					foreach($c_output->data as $key => $val) {
						$list[$site_srl_list[$val->site_srl]]->member_count = $val->count;
					}
				}
			}
			return $list;
		}

        /**
         * @brief 프로젝트 생성
         **/
        function dispProjectCreateProject() {
            $oProjectModel = &getModel('project');
            if(!$oProjectModel->isCreationGranted()) return new Object(-1,'alert_permission_denied_to_create');

            $project_config = $oProjectModel->getConfig($this->site_srl);
            Context::set('project_config', $project_config);

            Context::set('directories', $oProjectModel->getProjectAllDirectories());

            Context::addJsFilter($this->module_path.'tpl/filter', 'user_reserve_project.xml');
            $this->setTemplateFile('create');
        }


		function dispProject() {
            $oModuleModel = &getModel('module');
            Context::set('site_admins', $oModuleModel->getSiteAdmin($this->site_srl));
			Context::set('act', 'dispProject');
			
            $oProjectModel = &getModel('project');

            $project_config = $oProjectModel->getConfig(0);
            if($project_config->use_repos == 'Y')
            {
                $repos_info = $oProjectModel->getProjectReposInfo($this->site_srl);
                if($repos_info->repos_id)
                {
                    $repos_url = sprintf("http://%s/%s", $project_config->repos_url, $repos_info->repos_id);
                    Context::set("repos_url", $repos_url);
                }
            }

			$c_args->site_srl = $this->site_srl;
			$output = executeQueryArray("project.getProjectGroupMemberCount", $c_args);
			Context::set('member_groups', $output->data);
			$sum = 0;
			if(!$output->data) $output->data = array();
			foreach($output->data as $group)
			{
				$sum += $group->count;
			}
			Context::set('member_count', $sum);

			$output = executeQueryArray("project.getMilestonesHome", $c_args);
			if($output->data)
			{
				$oIssuetrackerModel =& getModel('issuetracker');
                foreach($output->data as $key => $milestone) {
					$issues = $oIssuetrackerModel->getIssueCountByStatus($milestone->milestone_srl, $milestone->module_srl);
                    $output->data[$key]->issues = $issues;
                }
				$output->data = array_reverse($output->data);
				Context::set('milestones', $output->data);
			}

			
			$page = Context::get('page');
			if(!$page) {
				$page = 1;
				Context::set('page', $page);
			}
			$output = $oProjectModel->getNewItems($page, $this->site_srl);
			Context::set('modules', $output->modules);
			Context::set('news_list', $output->data);
			Context::set('page_navigation', $output->page_navigation);

			$output = executeQueryArray("project.getNotices", $c_args);
			if($output->data)
			{
				$documents = array();
				$oDocumentModel =& getModel('document');
				foreach($output->data as $data)
				{
					$oDocument = $oDocumentModel->getDocument(); 
					$oDocument->setAttribute($data, false);
					$documents[] = $oDocument;
				}
				Context::set('notices', $documents);
			}

            $logged_info = Context::get('logged_info');
            if(count($logged_info->group_list)) Context::set('project_join', true);
            else Context::set('project_join', false);

            $this->setTemplateFile('project_home');
		}

        /**
         * @brief 개별 프로젝트 메인
         **/
        function dispProjectold() {
            $oModuleModel = &getModel('module');
            $oDocumentModel = &getModel('document');
            $oCommentModel = &getModel('comment');
            $oIssueTrackerModel = &getModel('issuetracker');
            $oProjectModel = &getModel('project');

            $issue_trackers = $oModuleModel->getModuleSrlByMid('issuetracker');
            $module_srl = $issue_trackers[0];

            // 프로젝트 현황 구함 (releases, packages, milestones)
            Context::set('status', $oIssueTrackerModel->getProjectInfo($module_srl));

            // 최근 이슈 구함 (신규 10개)
            $args->module_srl = $module_srl;
            $args->list_count = 10;
            $output = $oIssueTrackerModel->getIssueList($args);
            Context::set('issues', $output->data);

            // changeset
            $changesets = $oIssueTrackerModel->getChangesets($module_srl, null, 5, array('commit'),10);
            Context::set('changesets', $changesets);

            // 뉴스 추출
            $n_args->site_srl = $this->site_srl;
            $n_args->list_count = 5;
            $output = executeQuery('project.getNewsList', $n_args);
            Context::set('news', $output->data);

            // 구인 추출
            $output = executeQuery('project.getOfferList', $n_args);
            Context::set('offer', $output->data);

            // 프로젝트 멤버
            $c_args->site_srl = $this->site_srl;
            $output = executeQuery('project.getProjectMemberCount', $c_args);
            Context::set('member_count', $output->data->count);

            // 개발자 추출
            $output = executeQuery('project.getReposMemberCount', $c_args);
            Context::set('developer_count', $output->data->count);

            // release
            $rargs->site_srl = $this->site_srl;
            $rargs->list_count = 5;
            $output = executeQueryArray('project.getReleaseList', $rargs);
            if($output->data) {
                $oFileModel = &getModel('file');
                foreach($output->data as $key => $release) {
                    $files = $oFileModel->getFiles($release->release_srl);
                    $release->files = $files;
                    $output->data[$key] = $release;
                }
            }
            Context::set('releases', $output->data);

            // 사이트 가입 유무 체크
            $logged_info = Context::get('logged_info');
            if(count($logged_info->group_list)) Context::set('project_join', true);
            else Context::set('project_join', false);

            // 최신 글 추출
            $n_args->site_srl = $this->site_srl;
            $n_args->mid = "'wiki','forum'";
            $output = executeQueryArray('project.getNewestDocuments', $n_args);
            if($output->data) {
                foreach($output->data as $key => $attribute) {
		    unset($oDocument);
		    $oDocument = new documentItem();
                    $oDocument->setAttribute($attribute);
                    $output->data[$key] = $oDocument;
                }
            }
            Context::set('newest_documents', $output->data);
            
            // 최신 댓글 추출
            $output = executeQueryArray('project.getNewestComments', $n_args);
            if(count($output->data)) {
                foreach($output->data as $key => $val) {
                   unset($oComment);
                   $oComment = new commentItem();
                   $oComment->setAttribute($val); 
                   $output->data[$key] = $oComment;
                }
            }
            Context::set('newest_comments', $output->data);

            // 관리자를 구함
            Context::set('site_admins', $oModuleModel->getSiteAdmin($this->site_srl));

            // repository 정보
            $project_config = $oProjectModel->getConfig(0);
            if($project_config->use_repos == 'Y')
            {
                $repos_info = $oProjectModel->getProjectReposInfo($this->site_srl);
                if($repos_info->repos_id)
                {
                    $repos_url = sprintf("http://%s/%s", $project_config->repos_url, $repos_info->repos_id);
                    Context::set("repos_url", $repos_url);
                }
            }

            $this->setTemplateFile('project_home');
        }

        /**
         * @brief 프로젝트 기본 관리
         **/
        function dispProjectManage() {
            $oProjectModel = &getModel('project');

            $project_config = $oProjectModel->getConfig($this->site_srl);
            $project_config2 = $oProjectModel->getConfig();
            Context::set('project_site_config', $project_config);

            // 디렉토리 구함
            $directories = $oProjectModel->getProjectDirectories(0);

            // 각각의 서브디렉토리 구함
            if(count($directories)) {
                foreach($directories as $key => $val) {
                    $directories[$key]->childs = $oProjectModel->getProjectDirectories($key);
                }
            }
            Context::set('directories', $directories);
			$oModuleModel =& getModel('module');	
			$skin_info = $oModuleModel->loadSkinInfo($this->module_path, $project_config2->skin);
			if($skin_info->colorset)
			{
				Context::set('colorset', $skin_info->colorset);
			}

            $oModuleModel = &getModel('module');


            $this->setTemplateFile('project_setup');
        }

        /**
         * @brief 프로젝트 회원 그룹 관리
         **/
        function dispProjectMemberGroupManage() {
            // 멤버모델 객체 생성
            $oMemberModel = &getModel('member');

            // group_srl이 있으면 미리 체크하여 selected_group 세팅
            $group_srl = Context::get('group_srl');
            if($group_srl) {
                $selected_group = $oMemberModel->getGroup($group_srl);
                Context::set('selected_group',$selected_group);
            }

            // group 목록 가져오기
            $group_list = $oMemberModel->getGroups($this->site_srl);
            Context::set('group_list', $group_list);

            $this->setTemplateFile('group_list');
        }

        /**
         * @brief 프로젝트 모듈의 회원 관리
         **/
        function dispProjectMemberManage() {
            $oMemberModel = &getModel('member');

            // 회원 그룹을 구함
            $group_list = $oMemberModel->getGroups($this->site_srl);
            if(!$group_list) $group_list = array();
            Context::set('group_list', $group_list);

			// 회원 목록을 구함
            $args->selected_group_srl = Context::get('selected_group_srl');
            if(!isset($group_list[$args->selected_group_srl])) {
                $args->selected_group_srl = implode(',',array_keys($group_list));
            }
            $search_target = trim(Context::get('search_target'));
            $search_keyword = trim(Context::get('search_keyword'));
            if($search_target && $search_keyword) {
                switch($search_target) {
                    case 'user_id' :
                            if($search_keyword) $search_keyword = str_replace(' ','%',$search_keyword);
                            $args->s_user_id = $search_keyword;
                        break;
                    case 'user_name' :
                            if($search_keyword) $search_keyword = str_replace(' ','%',$search_keyword);
                            $args->s_user_name = $search_keyword;
                        break;
                    case 'nick_name' :
                            if($search_keyword) $search_keyword = str_replace(' ','%',$search_keyword);
                            $args->s_nick_name = $search_keyword;
                        break;
                    case 'email_address' :
                            if($search_keyword) $search_keyword = str_replace(' ','%',$search_keyword);
                            $args->s_email_address = $search_keyword;
                        break;
                    case 'regdate' :
                            $args->s_regdate = ereg_replace("[^0-9]","",$search_keyword);
                        break;
                    case 'regdate_more' :
                            $args->s_regdate_more = substr(ereg_replace("[^0-9]","",$search_keyword) . '00000000000000',0,14);
                        break;
                    case 'regdate_less' :
                            $args->s_regdate_less = substr(ereg_replace("[^0-9]","",$search_keyword) . '00000000000000',0,14);
                        break;
                    case 'last_login' :
                            $args->s_last_login = $search_keyword;
                        break;
                    case 'last_login_more' :
                            $args->s_last_login_more = substr(ereg_replace("[^0-9]","",$search_keyword) . '00000000000000',0,14);
                        break;
                    case 'last_login_less' :
                            $args->s_last_login_less = substr(ereg_replace("[^0-9]","",$search_keyword) . '00000000000000',0,14);
                        break;
                    case 'extra_vars' :
                            $args->s_extra_vars = ereg_replace("[^0-9]","",$search_keyword);
                        break;
                }
            }

		    $query_id = 'member.getMemberListWithinGroup';
            $sort_order = Context::get('sort_order');
            if($sort_order != "asc") $sort_order = "desc";
            $args->sort_order = $sort_order;
            Context::set('sort_order', $sort_order);
		    $args->sort_index = "member.member_srl";
            $args->page = Context::get('page');
            $args->list_count = 40;
            $args->page_count = 10;
            $output = executeQuery($query_id, $args);

            $members = array();
            if(count($output->data)) {
                foreach($output->data as $key=>$val) {
                    $members[] = $val->member_srl;
                }
            }

            $members_groups = $oMemberModel->getMembersGroups($members, $this->site_srl);
            Context::set('members_groups',$members_groups);

            // 템플릿에 쓰기 위해서 context::set
            Context::set('total_count', $output->total_count);
            Context::set('total_page', $output->total_page);
            Context::set('page', $output->page);
            Context::set('member_list', $output->data);
            Context::set('page_navigation', $output->page_navigation);

            $this->setTemplateFile('member_list');
        }

        /**
         * @brief 저장소 설정
         **/
        function dispProjectRepos() {
            $oProjectModel = &getModel('project');
            $oMemberModel = &getModel('member');

            // 저장소허용 여부 
            $project_config = $oProjectModel->getConfig(0);
            Context::set('use_repos', $project_config->use_repos);
            Context::set('repos_url', $project_config->repos_url);

            // repository 정보 설정
            Context::set('repos_info', $oProjectModel->getProjectReposInfo($this->site_srl));

            // repository 커밋 허용 그룹 추출
            $args->site_srl = $this->site_srl;
            $output = executeQueryArray('project.getProjectReposGroup', $args);
            if($output->data && count($output->data)) foreach($output->data as $key => $val) $repos_group[] = $val->group_srl;
            else $repos_group = array();
            Context::set('repos_group', $repos_group);

            // 그룹 목록
            $group_list = $oMemberModel->getGroups($this->site_srl);
            Context::set('group_list', $group_list);

            $this->setTemplateFile('project_repos');
        }

        /**
         * @brief 접속 통계
         **/
        function dispProjectCounter() {
            // 정해진 일자가 없으면 오늘자로 설정
            $selected_date = Context::get('selected_date');
            if(!$selected_date) $selected_date = date("Ymd");
            Context::set('selected_date', $selected_date);

            // counter model 객체 생성
            $oCounterModel = &getModel('counter');

            // 전체 카운터 및 지정된 일자의 현황 가져오기
            $status = $oCounterModel->getStatus(array(0,$selected_date),$this->site_srl);
            Context::set('total_counter', $status[0]);
            Context::set('selected_day_counter', $status[$selected_date]);

            // 시간, 일, 월, 년도별로 데이터 가져오기
            $type = Context::get('type');
            if(!$type) {
                $type = 'day';
                Context::set('type',$type);
            }
            $detail_status = $oCounterModel->getHourlyStatus($type, $selected_date, $this->site_srl);
            Context::set('detail_status', $detail_status);
            
            // 표시
            $this->setTemplateFile('site_status');
        }

        /**
         * @brief 애드온/ 컴포넌트 설정
         **/
        function dispProjectComponent() {
            // 애드온 목록을 가져옴
            $oAddonModel = &getAdminModel('addon');
            $addon_list = $oAddonModel->getAddonList($this->site_srl);
            Context::set('addon_list', $addon_list);

            // 에디터 컴포넌트 목록을 가져옴
            $oEditorModel = &getModel('editor');
            Context::set('component_list', $oEditorModel->getComponentList(false, $this->site_srl));

            // 표시
            $this->setTemplateFile('components');
        }

        /**
         * @brief 내 저장소계정 설정
         **/
        function dispProjectAccountManage() {

            // 나의 프로젝트 목록 구함
            $logged_info = Context::get('logged_info');
            if(!$logged_info->member_srl) return new Object(-1,'msg_invalid_request');

            $args->member_srl = $logged_info->member_srl;
            $output = executeQueryArray('project.getMemberRepos', $args);
            Context::set('dev_repos', $output->data);

            Context::addJsFilter($this->module_path.'tpl/filter', 'update_account.xml');
            $this->setTemplateFile('account_manage');
        }

        /**
         * @brief rss
         **/
        function rss() {
            $oRss = &getView('rss');
            $oDocumentModel = &getModel('document');
            $oModuleModel = &getModel('module');

            $project_info = $oModuleModel->getModuleConfig('project');
            if($project_info->use_rss != 'Y') return new Object(-1,'msg_rss_is_disabled');

            $output = executeQueryArray('project.getRssList', $args);
            if($output->data) {
                foreach($output->data as $key => $val) {
                    unset($obj);
                    $obj = new DocumentItem(0);
                    $obj->setAttribute($val);
                    $document_list[] = $obj;
                }
            }

            $oRss->rss($document_list, $project_info->browser_title);
            $this->setTemplatePath($oRss->getTemplatePath());
            $this->setTemplateFile($oRss->getTemplateFile());
        }

		/**
		 * @brief project member
		 **/
		function dispProjectMember() {
			$oMemberModel = &getModel('member');
			$oModuleModel = &getModel('module');

            // 그룹 세팅
            Context::set('group_list', $oMemberModel->getGroups($this->site_srl));

            // 관리자를 구함
            $site_admins = $oModuleModel->getSiteAdmin($this->site_srl);
            if(count($site_admins)) {
				foreach($site_admins as $key => $val) {
						$site_admins[$key]->profile_image = $oMemberModel->getProfileImage($val->member_srl);
				}
            }
            Context::set('site_admins', $site_admins);

            // 개발 권한이 있는 회원을 구함
            $args->site_srl = $this->site_srl;
			$output = executeQueryArray('project.getProjectMembers', $args);
			if($output->data) {
				foreach($output->data as $key => $val) {
						$output->data[$key]->profile_image = $oMemberModel->getProfileImage($val->member_srl);
				}
			}

			Context::set('members', $output->data);
            $this->setTemplateFile('members');
		}

        /**
         * @brief news
         **/
        function dispProjectNews() {
            $args->site_srl = $this->site_srl;
            $args->page = Context::get('page');
            $output = executeQuery('project.getNewsList', $args);
            Context::set('news_list', $output->data);
            Context::set('total_count', $output->total_count);
            Context::set('total_page', $output->total_page);
            Context::set('page', $output->page);
            Context::set('page_navigation', $output->page_navigation);
            $this->setTemplateFile('news');
        }

        /**
         * @brief post news
         ***/
        function dispProjectPostNews() {
			$oEditorModel = &getModel('editor');
			$option->skin = 'xpresseditor';
			$option->primary_key_name = 'news_srl';
			$option->content_key_name = 'content';
			$option->allow_fileupload = false;
			$option->enable_autosave = false;
			$option->enable_default_component = true;
			$option->enable_component = true;
			$option->resizable = true;
			$option->height = 500;
			$editor = $oEditorModel->getEditor(0, $option);
            Context::set('editor', $editor);

            $news_srl = Context::get('news_srl');
            if($news_srl) {
                $args->news_srl = $news_srl;
                $output = executeQuery('project.getNews', $args);
                if($output->data) Context::set('news', $output->data);
            }

            Context::addJsFilter($this->module_path.'tpl/filter','insert_news.xml');
            $this->setTemplateFile('post_news');
        }

        /**
         * @brief offer
         **/
        function dispProjectOffer() {
            $args->site_srl = $this->site_srl;
            $args->page = Context::get('page');
            $output = executeQuery('project.getOfferList', $args);
            Context::set('offer_list', $output->data);
            Context::set('total_count', $output->total_count);
            Context::set('total_page', $output->total_page);
            Context::set('page', $output->page);
            Context::set('page_navigation', $output->page_navigation);
            $this->setTemplateFile('offer');
        }

        /**
         * @brief post offer
         ***/
        function dispProjectPostOffer() {
			$oEditorModel = &getModel('editor');
			$option->skin = 'xpresseditor';
			$option->primary_key_name = 'offer_srl';
			$option->content_key_name = 'content';
			$option->allow_fileupload = false;
			$option->enable_autosave = false;
			$option->enable_default_component = true;
			$option->enable_component = true;
			$option->resizable = true;
			$option->height = 500;
			$editor = $oEditorModel->getEditor(0, $option);
            Context::set('editor', $editor);

            $offer_srl = Context::get('offer_srl');
            if($offer_srl) {
                $args->offer_srl = $offer_srl;
                $output = executeQuery('project.getOffer', $args);
                if($output->data) Context::set('offer', $output->data);
            }

            Context::addJsFilter($this->module_path.'tpl/filter','insert_offer.xml');
            $this->setTemplateFile('post_offer');
        }

		function dispProjectContributors() {
			$oModel =& getModel('project');
			$page = Context::get('page');
			if(!$page) {
				$page = 1;
				Context::set('page', $page);
			}

			$sort_order = Context::get('sort_order');
			if(!$sort_order) {
				$sort_order = "points";
				Context::set('sort_order', $sort_order);
			}

			$search_keyword = Context::get('search_keyword');
			if($search_keyword)
			{
				$output = $oModel->getContributor($search_keyword);
			}
			else
			{
				$output = $oModel->getContributors($sort_order, $page, $search_keyword);
			}

			if($output->data)
			{
				$args->site_srls = implode(",", array_keys($output->site_srls));
				$output2 = executeQueryArray("project.getProjectsBySiteSrl", $args);
				$projects = array();
				foreach($output2->data as $data)
				{
					$projects[$data->site_srl] = $data;	
				}
				Context::set('projects', $projects);
			}
			Context::set('contributors', $output->data);
			if($output->page_navigation)
				Context::set('page_navigation', $output->page_navigation);
			else
				Context::set('page_navigation', new PageHandler(0, 0, 1, 10));
            $this->setTemplateFile('contributors');
		}
    }
?>
