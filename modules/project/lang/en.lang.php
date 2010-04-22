<?php
    $lang->project = 'ProjectXE'; 
    $lang->project_id = "프로젝트 접속 ID"; 
    $lang->project_title = '프로젝트 이름';
    $lang->project_description = '프로젝트 설명';
    $lang->project_logo = '프로젝트 로고 이미지';
    $lang->project_banner = '프로젝트 배너이미지';
    $lang->item_group_grant = '보여줄 그룹';
    $lang->project_info = 'Project Info.';
    $lang->project_admin = 'Administrator';
    $lang->do_selected_member = '선택된 회원을 : ';
    $lang->project_latest_documents = '프로젝트 최신 글';
    $lang->project_latest_comments = '프로젝트 최신 댓글';
    $lang->myproject_list = '가입한 프로젝트';
    $lang->project_creation_type = '프로젝트 접속 방법';
    $lang->project_directory = '프로젝트 디렉토리';
    $lang->root_directory = '상위 디렉토리';
    $lang->sub_directory = '하위 디렉토리';
    $lang->project_creation_privilege = '프로젝트 생성 권한';
    $lang->project_main_mid = '프로젝트 메인 ID';
    $lang->project_main_layout = '프로젝트 메인 layout';
    $lang->project_repos = '저장소 설정';
    $lang->disable_repos = '저장소 호스팅 지원이 되지 않습니다';
    $lang->summary = '요약';
    $lang->repos_title = '저장소 이름 ';
    $lang->repos_id = '저장소 ID';
    $lang->repos_url = '저장소 URL';
    $lang->project_allow_commit = '커밋 허용 그룹';
    $lang->repository = "저장소";
    $lang->repository_url = "저장소 URL";
    $lang->project_main_url = "프로젝트 메인";
    $lang->my_projects = "참가 프로젝트";
    $lang->release = "릴리즈";
    $lang->packages = '패키지';
    $lang->timeline = '타임라인';
    $lang->download = '다운로드';
    $lang->project_list = '프로젝트';
    $lang->project_member_count = '가입자 수';
    $lang->developer_member_count = '개발자 수';
    $lang->project_rank = '순위';
    $lang->my_project = '나의 프로젝트';
    $lang->project_home = '프로젝트 홈';
    $lang->top_activation_project = '활성화된 프로젝트 (지난 1주일 기준)';
	$lang->rank_activation = '활성화';
    $lang->newest_project = '최신 프로젝트';
	$lang->order_target = '정렬 순서';
	$lang->created_date = '생성일';
	$lang->creation_agreement = '생성 약관';
	$lang->agree_agreement = '약관에 동의합니다';
    $lang->project_admin_menus = array(
        'dispProjectAdminContent' => '프로젝트 목록',
        'dispProjectAdminReserved' => '대기 목록',
        'dispProjectAdminCreate' => '생성',
        'dispProjectAdminConfig' => '기능 설정',
        'dispProjectAdminSkinSetup' => '메인 스킨 설정',
        'dispProjectAdminGrantInfo' => '권한 설정',
        'dispProjectAdminDirectorySetup' => '디렉토리 설정',
    );
	$lang->project_default_menus = array(
		'Milestone' => array('issuetracker','dispIssuetrackerViewMilestone'),
        'Wiki' => array('wiki','dispWikiContent'),
        'Forum' => array('forum','dispBoardContent'),
        'Issue' => array('issuetracker','dispIssuetrackerViewIssue', array(
			'Issue List' => array('issuetracker', 'dispIssuetrackerViewIssue'),
			'New Issue' => array('issuetracker', 'dispIssuetrackerNewIssue'))
		),
        'Source' => array('issuetracker','dispIssuetrackerViewSource', array(
			'View Source' => array('issuetracker', 'dispIssuetrackerViewSource'))
		),
        'Timeline' => array('issuetracker','dispIssuetrackerTimeline'), 
        'Download' => array('issuetracker','dispIssuetrackerDownload') );
	
	$lang->project_setting_menu = array(
			'프로젝트 설정' => array('project','dispProjectManage'),
			'그룹 설정' => array('project','dispProjectMemberGroupManage'),
			'회원 관리 ' => array('project','dispProjectMemberManage'),
			'포럼' => array('forum','dispBoardAdminInsertBoard'),
			'이슈트래커' => array('issuetracker','dispIssuetrackerAdminProjectInfo'),
			'위키' => array('wiki','dispWikiAdminInsertWiki'),
			'접속 통계' => array('project','dispProjectCounter'),
			'기능 설정' => array('project',"dispProjectComponent"),
			'저장소 설정' => array('project',"dispProjectRepos") );
		
    $lang->project_default_menus_o = array(
        '처음' => array('project','', array(
                '뉴스' => array('project','dispProjectNews'),
                '구인' => array('project','dispProjectOffer'),
                '참여 회원' => array('project','dispProjectMember'),
            
            )),
        '위키' => array('wiki','dispWikiContent'),
        '포럼' => array('forum','dispBoardContent'),
        '이슈목록' => array('issuetracker','dispIssuetrackerViewIssue', array(
                '이슈목록' => array('issuetracker','dispIssuetrackerViewIssue'),
                '이슈등록' => array('issuetracker','dispIssuetrackerNewIssue'),
            )),
        '개발현황' => array('issuetracker','dispIssuetrackerViewMilestone',array(
                '개발현황' => array('issuetracker','dispIssuetrackerViewMilestone'),
                '타임라인' => array('issuetracker','dispIssuetrackerTimeline'),
                '코드열람' => array('issuetracker','dispIssuetrackerViewSource'),
            )),
        '다운로드 ' => array('issuetracker','dispIssuetrackerDownload'),
        '프로젝트 설정' => array('project','dispProjectManage', array(
                '프로젝트 설정' => array('project','dispProjectManage'),
                '그룹 설정' => array('project','dispProjectMemberGroupManage'),
                '회원 관리 ' => array('project','dispProjectMemberManage'),
                '포럼' => array('forum','dispBoardAdminInsertBoard'),
                '이슈트래커' => array('issuetracker','dispIssuetrackerAdminProjectInfo'),
                '위키' => array('wiki','dispWikiAdminInsertWiki'),
                '접속 통계' => array('project','dispProjectCounter'),
                '기능 설정' => array('project',"dispProjectComponent"),
                '저장소 설정' => array('project',"dispProjectRepos"),
            )),
    );
    $lang->project_member_group = array(
        'observer' => '대기회원',
        'regular' => '정회원',
        'commiter' => '개발자',
    );
    $lang->default_language = '기본 언어';

    $lang->project_home_title = array(
        'news' => '알립니다',
        'offer' => '프로젝트 멤버 모집',
        'release' => '다운로드',
        'milestone' => '개발계획',
        'issues' => '최근 이슈',
        'changeset' => '변경기록',
        'newestDocument' => '최근 글',
        'newestComment' => '최근 댓글',
    );

    $lang->notify_mail = '메일 통보';
    $lang->about_notify_mail = '프로젝트의 등록 요청이 있을 때 메일을 발송받을 수 있는 이메일 주소를 입력해주세요.';

    $lang->cmd_make_project = '프로젝트 생성';
    $lang->cmd_import = '가져오기';
    $lang->cmd_make_directory = '디렉토리 생성';
    $lang->cmd_make_sub_directory = '하위 디렉토리 생성';
    $lang->cmd_export = '내보내기';
    $lang->cmd_repos_repository = '저장소 저장소 생성';
    $lang->cmd_manage_project_account = '저장소 계정 설정';
    $lang->cmd_manage_member_group ='회원 그룹 관리';
    $lang->cmd_manage_member_list ='회원 목록 관리';
    $lang->cmd_manage_components ='기능 설정';
    $lang->cmd_project_registration = 'Project 생성';
    $lang->cmd_directory_registration = '디렉토리 생성';
    $lang->cmd_project_setup = '프로젝트 설정';
    $lang->cmd_project_delete = '프로젝트 삭제';
    $lang->cmd_go_home = '홈으로 이동';
    $lang->cmd_go_project_admin = 'Project 전체 관리';
    $lang->cmd_select_index = '초기화면 선택';
    $lang->cmd_add_new_menu = '새로운 메뉴 추가';
    $lang->cmd_post_news = '새로운 뉴스 등록';
    $lang->cmd_post_offer = '새로운 구인게시물 등록';
    $lang->cmd_make_my_project = '내 프로젝트 생성';

    $lang->cmd_reject_project_creation = 'Deny';
    $lang->cmd_accept_project_creation = 'Approve';

    $lang->about_project_main_mid = '프로젝트 메인 페이지를 http://주소/ID 값으로 접속하기 위한 ID값을 입력해주세요.';
    $lang->about_project_rss = 'Project 메인페이지의 RSS 출력을 허용할 수 있습니다';
    $lang->about_project_repos = '프로젝트 관리자들이 저장소을 생성할 수 있도록 할 수 있습니다';
    $lang->about_project_repos_url = '개별 프로젝트에 제공할 저장소의 주소를 입력해주세요.<br/>저장소은 입력하신 주소의 서브도메인으로 정해집니다.<br/>repos.xpressengine.com 으로 입력하시면 저장소의 주소는 http://repos.xpressengine.com/저장소ID 으로 됩니다';
    $lang->about_project_main_url = '개별 프로젝트에서 프로젝트 메인으로 돌아가기 위한 URL을 입력해주세요';
    $lang->about_repos_title = '저장소 접속시 출력되는 저장소의 이름을 정해주세요<br/>(2~40자 사이)';
    $lang->about_repos_id = '저장소 ID는 서비스 제공자가 제공하는 저장소 주소의 구분을 위해서 필요합니다.<br/>한번 생성하면 변경할 수 없습니다<br/>(영문으로 시작하고 영문/숫자/_ 사용 가능, 2~20자 사이)';
    $lang->about_project_allow_commit = '선택된 그룹의 회원들은 저장소에 커밋할 수 있습니다. 변경시 기존 권한이 있는 회원들의 권한이 박탈됩니다';
    $lang->about_manage_project_account = '프로젝트의 커밋권한이 있을 경우 저장소 계정 설정을 해주어야 실제 저장소에 접근할 수 있습니다.<br/>저장소의 계정 아이디는 지금 로그인하신 아이디와 동일하며 비밀번호는 다르게 설정할 수 있습니다.<br/>하나의 저장소 계정으로 이 사이트의 모든 프로젝트 저장소에 접근할 수 있습니다';
    $lang->about_move_module = '가상사이트와 기본사이트간의 모듈을 옮길 수 있습니다.<br/>다만 가상사이트끼리 모듈을 이동하거나 같은 이름의 mid가 있을 경우 예기치 않은 오류가 생길 수 있으니 꼭 가상 사이트와 기본 사이트간의 다른 이름을 가지는 모듈만 이동하세요';
    $lang->about_default_language = '처음 접속하는 사용자의 언어 설정을 지정할 수 있습니다.';
    $lang->about_project_act = array(
        'dispProjectManage' => '프로젝트의 소개 및 디렉토리등을 설정할 수 있습니다',
        'dispProjectMemberGroupManage' => 'Project 내에서 사용되는 그룹 관리를 할 수 있습니다',
        'dispProjectMemberManage' => 'Project에 등록된 회원들을 보거나 관리할 수 있습니다',
        "dispProjectComponent" => "에디터 컴포넌트/ 애드온을 활성화 하거나 설정을 변경할 수 있습니다",
        'dispProjectCounter' => 'Project의 접속 현황을 볼 수 있습니다',
        'dispProjectRepos' => 'Project에서 사용할 저장소 호스팅을 이용할 수 있습니다',
    );
    $lang->about_project = 'Project 서비스 관리자는 다수의 Project를 만들 수 있고 또 각 Project를 편하게 설정할 수 있도록 합니다.';
    $lang->about_menu_names = 'Project에 나타날 메뉴 이름을 언어에 따라서 지정할 수 있습니다.<br/>하나만 입력하셔도 모두 같이 적용됩니다';
    $lang->about_menu_option = '메뉴를 선택시 새창으로 열지를 선택할 수 있습니다.<br />펼침 메뉴는 레이아웃에 따라 동작합니다';
    $lang->about_group_grant = '그룹을 선택하면 선택된 그룹만 메뉴가 보입니다.<br/>모두 해제하면 비회원도 볼 수 있습니다';
    $lang->about_module_type = '게시판,페이지는 모듈을 생성하고 URL은 링크만 합니다.<br/>생성후 수정할 수 없습니다';
    $lang->about_browser_title = '메뉴에 접속시 브라우저의 제목으로 나타날 내용입니다';
    $lang->about_module_id = '게시판,페이지등 접속할때 사용될 주소입니다.<br/>예) http://도메인/[모듈ID], http://도메인/?mid=[모듈ID]';
    $lang->about_menu_item_url = '대상을 URL로 할때 연결할 링크주소입니다.<br/>http://는 빼고 입력해주세요';
    $lang->about_menu_image_button = '메뉴명 대신 이미지로 메뉴를 사용할 수 있습니다.';
    $lang->about_project_delete = 'Project를 삭제하게 되면 연결되어 있는 모든 모듈(게시판,페이지등)과 그에 따른 글들이 삭제됩니다.<br />주의가 필요합니다';
    $lang->about_project_admin = 'Project 관리자를 설정할 수 있습니다.<br/>Project 관리자는 http://주소/?act=dispProjectManage 로 관리자 페이지로 접속할 수 있으며 존재하지 않는 사용자는 관리자로 등록되지 않습니다';
    $lang->about_project_creation_type = '사용자들이 프로젝트를 생성할때 프로젝트 접속 방법을 정해야 합니다. Site ID는 http://기본주소/ID 로 접속 가능하고 Domain 접속은 입력하신 도메인의 2차 도메인(http://domain.mydomain.net) 으로 프로젝트가 생성됩니다';
    $lang->about_forum_description = '프로젝트에 대한 다양한 이야기를 나누는 포럼입니다.<br/>프로젝트는 좋은 의견과 참여로 발전할 수 있습니다';
    $lang->about_project_id = '프로젝트 접속 아이디는 생성된 프로젝트의 주소로 사용됩니다.<br/>(4~12자 사이이며 첫글자는 영문으로, 두번 글자부터는 영문과 숫자만 쓸 수 있습니다)';
    $lang->about_project_title = '프로젝트의 제목을 입력해주세요. (4~20자 사이)';
    $lang->about_project_description = '프로젝트를 소개할 수 있는 간단한 내용을 입력해주세요.<br/>가능하면 프로젝트의 특징과 정보를 알 수 있도록 해주시면 좋습니다. (10~200자 사이)';
    $lang->about_project_directory = '프로젝트를 손쉽게 찾아 갈 수 있도록 생성하시려는 프로젝트의 디렉토리를 설정해주세요.<br />프로젝트가 쉽게 찾아지고 활용될 수 있는데 매우 중요한 정보입니다';
    $lang->about_project_logo = '프로젝트 상단 또는 프로젝트 전체 목록에서 나타나는 로고 이미지를 입력해주세요.';
    $lang->about_project_banner = '프로젝트 초기화면에 나타나는 배너 이미지를 입력해주세요. (가로- 200px)';

    $lang->msg_wait_repos_create = "저장소 저장소는 즉시 생성되지 않습니다.\n\n문의 사항이 있으시면 사이트 운영자에게 문의 바랍니다";
    $lang->msg_need_one_group = '커밋 허용 그룹은 1개 이상 지정되어 있어야 합니다';
    $lang->msg_module_count_exceed = '허용된 모듈의 개수를 초과하였기에 생성할 수 없습니다';
    $lang->msg_not_enabled_id = '사용할 수 없는 아이디입니다';
    $lang->msg_wrong_project_id = '프로젝트 접속 아이디는 4~12자 사이이며 첫글자는 영문으로, 두번 글자부터는 영문과 숫자만 쓸 수 있습니다';
    $lang->msg_same_site = '동일한 가상 사이트의 모듈은 이동할 수가 없습니다';
    $lang->msg_no_data = '등록된 내용이 없습니다';

    $lang->msg_project_reserved = '프로젝트 생성 요청이 접수되었습니다.\n접수된 내용을 바탕으로 프로젝트 관리자가 심사 후 승인 여부를 결정하게 됩니다.\n감사합니다';

    $lang->confirm_delete_menu_item = '메뉴 항목 삭제시 연결되어 있는 게시판이나 페이지 모듈도 같이 삭제가 됩니다. 그래도 삭제하시겠습니까?';

    $lang->alert_permission_denied_to_create = '프로젝트를 생성할 수 있는 권한이 없습니다';
    $lang->alert_project_id_size_is_wrong = '프로젝트 ID의 글자 길이는 4자 이상 12자 이하여야 합니다';
    $lang->alert_project_title_size_is_wrong = '프로젝트 제목의 글자 길이는 4자 이상 20자 이하여야 합니다';
    $lang->alert_project_description_size_is_wrong = '프로젝트 설명의 글자 길이는 10자 이상 200자 이하여야 합니다';

    $lang->msg_notify_reserved_title = '프로젝트 등록 요청';
    $lang->msg_notify_reserved_content = '프로젝트 등록 요청이 있습니다.<br/>바로 가기 : <a href="%s">%s</a><br/>';
	$lang->date_opened = "Date Opened";
	$lang->write_to_forum = "New Article";
	$lang->write_new_issue = "New Issue";
	$lang->member_unit = "person(s)";
	$lang->profile = "Profile";
	$lang->my_projects = "My Projects";
	$lang->contributor_sort_order = array( "points" => "공헌지수 순", "articles" => "글 순", "comments" => "댓글 순", "commits" => "커밋 순" );
	$lang->mysummary_sort_order = array( "point" => "공헌지수 순", "member_count" => "멤버수 순", "regdate" => "개설일 최신 순", "joindate" => "가입일 최신 순");
	$lang->summary_sort_order = array( "point" => "활성화지수 순", "member_count" => "멤버수 순", "regdate" => "개설일 최신 순" );
	$lang->download_sort_order = array( "download_count" => "다운로드 순", "regdate" => "등록일 최신 순", "oldregdate" => "등록일 오랜 순");
	$lang->projectmilestone = "Milestone";
	$lang->project_title = "Project Title";
	$lang->my_activities = "My Activities";
	$lang->project_setting = "Project Setting";
	$lang->download_program = "Download Program";
?>
