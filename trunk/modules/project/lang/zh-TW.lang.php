<?php
    /**
     * @file   modules/project/lang/zh-TW.lang.php
     * @author zero (zero@nzeo.com) 翻譯：royallin
     * @brief  ProjectXE(project) 正體中文語言
     **/

    $lang->project = 'ProjectXE';
    $lang->project_id = "Project訪問 ID";
    $lang->project_title = 'Project標題';
    $lang->project_description = '簡介';
    $lang->project_logo = 'Logo 圖片';
    $lang->project_banner = 'banner';
    $lang->item_group_grant = '群組顯示';
    $lang->project_info = '資料';
    $lang->project_admin = '管理者';
    $lang->do_selected_member = '會員選擇 : ';
    $lang->project_latest_documents = '最新主題';
    $lang->project_latest_comments = '最新回覆';
    $lang->myproject_list = '已加入的project';
    $lang->project_creation_type = '訪問方式';
    $lang->project_directory = 'project目錄';
    $lang->root_directory = '根目錄';
    $lang->sub_directory = '子目錄';
    $lang->project_creation_privilege = '建立權限';
    $lang->project_main_mid = 'project 主要 ID';
    $lang->project_main_layout = 'project 主要版面';
    $lang->project_repos = '檔案庫設定';
    $lang->disable_repos = '檔案庫主機不支援';
    $lang->summary = '總覽';
    $lang->repos_title = '檔案庫標題';
    $lang->repos_id = '檔案庫 ID';
    $lang->repos_url = '檔案庫 URL';
    $lang->project_allow_commit = '允許提交群組';
    $lang->repository = "檔案庫";
    $lang->repository_url = "檔案庫網址";
    $lang->project_main_url = "project 首頁";
    $lang->my_projects = "參加 project";
    $lang->release = "發佈";
    $lang->packages = '套裝軟體';
    $lang->timeline = '時間軸';
    $lang->download = '下載';
    $lang->project_list = 'project列表';
    $lang->project_member_count = '會員數量';
    $lang->developer_member_count = '開發者數量';
    $lang->project_rank = '排行';
    $lang->my_project = '我的 project';
    $lang->project_home = 'Project Home';
    $lang->top_activation_project = '活躍的 projects (以一周為主)';
	$lang->rank_activation = '活躍排行';
    $lang->newest_project = '最新project';
	$lang->order_target = '排列順序';
	$lang->created_date = '建立日期';
	$lang->creation_agreement = '建立條件';
	$lang->agree_agreement = '同意條款';
    $lang->project_admin_menus = array(
        'dispProjectAdminContent' => '列表',
        'dispProjectAdminReserved' => '等候',
        'dispProjectAdminCreate' => '建立',
        'dispProjectAdminConfig' => '功能設定',
        'dispProjectAdminSkinSetup' => '面板設定',
        'dispProjectAdminGrantInfo' => '訪問設定',
        'dispProjectAdminDirectorySetup' => '目錄設定',
    );
	$lang->project_default_menus = array(
		'版本' => array('issuetracker','dispIssuetrackerViewMilestone'),
        '維基' => array('wiki','dispWikiContent'),
        '論壇' => array('forum','dispBoardContent'),
        '問題' => array('issuetracker','dispIssuetrackerViewIssue', array(
			'閱覽問題' => array('issuetracker', 'dispIssuetrackerViewIssue'),
			'發表問題' => array('issuetracker', 'dispIssuetrackerNewIssue'))
		),
        '原始碼' => array('issuetracker','dispIssuetrackerViewSource', array(
			'檢視' => array('issuetracker', 'dispIssuetrackerViewSource'))
		),
        '時間軸' => array('issuetracker','dispIssuetrackerTimeline'), 
        '檔案下載' => array('issuetracker','dispIssuetrackerDownload') );
	
	$lang->project_setting_menu = array(
			'管理' => array('project','dispProjectManage'),
			'群組設定' => array('project','dispProjectMemberGroupManage'),
			'會員管理 ' => array('project','dispProjectMemberManage'),
			'論壇' => array('forum','dispBoardAdminInsertBoard'),
			'問題追蹤' => array('issuetracker','dispIssuetrackerAdminProjectInfo'),
			'維基百科' => array('wiki','dispWikiAdminInsertWiki'),
			'訪問統計' => array('project','dispProjectCounter'),
			'功能設定' => array('project',"dispProjectComponent"),
			'儲存設定' => array('project',"dispProjectRepos") );
		
    $lang->project_default_menus_o = array(
        '首頁' => array('project','', array(
                '新聞' => array('project','dispProjectNews'),
                '求救' => array('project','dispProjectOffer'),
                '參加會員' => array('project','dispProjectMember'),
            
            )),
        '維基' => array('wiki','dispWikiContent'),
        '論壇' => array('forum','dispBoardContent'),
        '問題' => array('issuetracker','dispIssuetrackerViewIssue', array(
                '閱覽問題' => array('issuetracker','dispIssuetrackerViewIssue'),
                '發表問題' => array('issuetracker','dispIssuetrackerNewIssue'),
            )),
        '開發狀況' => array('issuetracker','dispIssuetrackerViewMilestone',array(
                '開發狀況' => array('issuetracker','dispIssuetrackerViewMilestone'),
                '時間軸' => array('issuetracker','dispIssuetrackerTimeline'),
                '檢視原始碼' => array('issuetracker','dispIssuetrackerViewSource'),
            )),
        '檔案下載' => array('issuetracker','dispIssuetrackerDownload'),
        'project設定' => array('project','dispProjectManage', array(
                'project設定' => array('project','dispProjectManage'),
                '群組設定' => array('project','dispProjectMemberGroupManage'),
                '會員管理' => array('project','dispProjectMemberManage'),
                '討論板' => array('forum','dispBoardAdminInsertBoard'),
                '問題追蹤' => array('issuetracker','dispIssuetrackerAdminProjectInfo'),
                '共筆系統' => array('wiki','dispWikiAdminInsertWiki'),
                '訪問統計' => array('project','dispProjectCounter'),
                '功能設定' => array('project',"dispProjectComponent"),
                '檔案庫設定' => array('project',"dispProjectRepos"),
            )),
    );
    $lang->project_member_group = array(
        'observer' => '대기회원',
        'regular' => '正會員',
        'commiter' => '提交者',
    );
    $lang->default_language = '預設語言';

    $lang->project_home_title = array(
        'news' => '公告事項',
        'offer' => '會員招募',
        'release' => '檔案下載',
        'milestone' => '開發計畫',
        'issues' => '最新問題',
        'changeset' => '變更紀錄',
        'newestDocument' => '最新主題',
        'newestComment' => '最新回覆',
    );

    $lang->notify_mail = '郵件通知';
    $lang->about_notify_mail = '請輸入郵件位址，當有 project 註冊請求時會用郵件通知。';

    $lang->cmd_make_project = '建立 Project';
    $lang->cmd_import = '匯入';
    $lang->cmd_make_directory = '建立目錄';
    $lang->cmd_make_sub_directory = '建立子目錄';
    $lang->cmd_export = '匯出';
    $lang->cmd_repos_repository = '建立檔案庫記憶域';
    $lang->cmd_manage_project_account = '檔案庫帳戶設定';
    $lang->cmd_manage_member_group ='群組管理';
    $lang->cmd_manage_member_list ='會員列表';
    $lang->cmd_manage_components ='功能設定';
    $lang->cmd_project_registration = 'Project 建立';
    $lang->cmd_directory_registration = '目錄建立';
    $lang->cmd_project_setup = 'project設定2';
    $lang->cmd_project_delete = '刪除 project';
    $lang->cmd_go_home = '返回首頁';
    $lang->cmd_go_project_admin = 'Project 整體管理';
    $lang->cmd_select_index = '選擇首頁';
    $lang->cmd_add_new_menu = '新增選單';
    $lang->cmd_post_news = '새로운 뉴스 등록';
    $lang->cmd_post_offer = '새로운 구인게시물 등록';
    $lang->cmd_make_my_project = '建立自己的 project';

    $lang->cmd_reject_project_creation = '禁止';
    $lang->cmd_accept_project_creation = '允許';

    $lang->about_project_main_mid = 'project的網址是「http://網址/ID」，請輸入想要的 ID 值。';
    $lang->about_project_rss = '可選擇是否輸出 Project 首頁 RSS 內容。';
    $lang->about_project_repos = 'project管理員可幫你建立檔案庫。';
    $lang->about_project_repos_url = '提供個人 project 檔案庫，請輸入網址。<br/>所輸入的網址必須是子域名。<br/>輸入 repos.xpressengine.com 的話，檔案庫網址就是 http://repos.xpressengine.com/檔案庫 ID 了。';
    $lang->about_project_main_url = '請輸入 project 首頁網址。';
    $lang->about_repos_title = '請決定檔案庫名稱。<br/>( 2 ~ 40個字之間 )';
    $lang->about_repos_id = '저장소 ID는 서비스 제공자가 제공하는 저장소 주소의 구분을 위해서 필요합니다.<br/>한번 생성하면 변경할 수 없습니다<br/>(可使用英文/數字/底線，開頭必須是英文，長度 2~20 個字之間)';
    $lang->about_project_allow_commit = '선택된 그룹의 회원들은 저장소에 커밋할 수 있습니다. 변경시 기존 권한이 있는 회원들의 권한이 박탈됩니다';
    $lang->about_manage_project_account = '프로젝트의 커밋권한이 있을 경우 저장소 계정 설정을 해주어야 실제 저장소에 접근할 수 있습니다.<br/>저장소의 계정 아이디는 지금 로그인하신 아이디와 동일하며 비밀번호는 다르게 설정할 수 있습니다.<br/>하나의 저장소 계정으로 이 사이트의 모든 프로젝트 저장소에 접근할 수 있습니다';
    $lang->about_move_module = '虛擬網站模組間的移動。<br/>다만 가상사이트끼리 모듈을 이동하거나 같은 이름의 mid가 있을 경우 예기치 않은 오류가 생길 수 있으니 꼭 가상 사이트와 기본 사이트간의 다른 이름을 가지는 모듈만 이동하세요';
    $lang->about_default_language = '請選擇用戶預設語言。';
    $lang->about_project_act = array(
        'dispProjectManage' => '프로젝트의 소개 및 디렉토리등을 설정할 수 있습니다',
        'dispProjectMemberGroupManage' => 'Project 내에서 사용되는 그룹 관리를 할 수 있습니다',
        'dispProjectMemberManage' => 'Project에 등록된 회원들을 보거나 관리할 수 있습니다',
        "dispProjectComponent" => "에디터 컴포넌트/ 애드온을 활성화 하거나 설정을 변경할 수 있습니다",
        'dispProjectCounter' => 'Project의 접속 현황을 볼 수 있습니다',
        'dispProjectRepos' => 'Project에서 사용할 저장소 호스팅을 이용할 수 있습니다',
    );
    $lang->about_project = 'Project 服務管理者可以建立 Project同時也能夠輕鬆地對每一個 Project進行設定。';
    $lang->about_menu_names = 'Project에 나타날 메뉴 이름을 언어에 따라서 지정할 수 있습니다.<br/>하나만 입력하셔도 모두 같이 적용됩니다';
    $lang->about_menu_option = '메뉴를 선택시 새창으로 열지를 선택할 수 있습니다.<br />펼침 메뉴는 레이아웃에 따라 동작합니다';
    $lang->about_group_grant = '그룹을 선택하면 선택된 그룹만 메뉴가 보입니다.<br/>모두 해제하면 비회원도 볼 수 있습니다';
    $lang->about_module_type = '게시판,페이지는 모듈을 생성하고 URL은 링크만 합니다.<br/>생성후 수정할 수 없습니다';
    $lang->about_browser_title = '메뉴에 접속시 브라우저의 제목으로 나타날 내용입니다';
    $lang->about_module_id = '게시판,페이지등 접속할때 사용될 주소입니다.<br/>例) http://域名/[模組ID], http://域名/?mid=[模組ID]';
    $lang->about_menu_item_url = '대상을 URL로 할때 연결할 링크주소입니다.<br/>「http://」不用輸入。';
    $lang->about_menu_image_button = '메뉴명 대신 이미지로 메뉴를 사용할 수 있습니다.';
    $lang->about_project_delete = 'Project를 삭제하게 되면 연결되어 있는 모든 모듈(게시판,페이지등)과 그에 따른 글들이 삭제됩니다.<br />주의가 필요합니다';
    $lang->about_project_admin = 'Project 관리자를 설정할 수 있습니다.<br/>Project 관리자는 http://주소/?act=dispProjectManage 로 관리자 페이지로 접속할 수 있으며 존재하지 않는 사용자는 관리자로 등록되지 않습니다';
    $lang->about_project_creation_type = '請選擇用戶建立 project 時的訪問方式。網站 ID 可透過 http://網址/ID 訪問； 網域名稱則是輸入子域名 (http://domain.mydomain.net) 的方式訪問。';
    $lang->about_forum_description = '프로젝트에 대한 다양한 이야기를 나누는 포럼입니다.<br/>프로젝트는 좋은 의견과 참여로 발전할 수 있습니다';
    $lang->about_project_id = '프로젝트 접속 아이디는 생성된 프로젝트의 주소로 사용됩니다.<br/>(4~12자 사이이며 첫글자는 영문으로, 두번 글자부터는 영문과 숫자만 쓸 수 있습니다)';
    $lang->about_project_title = '請輸入 project 標題。(4~20 之間)';
    $lang->about_project_description = '프로젝트를 소개할 수 있는 간단한 내용을 입력해주세요.<br/>가능하면 프로젝트의 특징과 정보를 알 수 있도록 해주시면 좋습니다. (10~200자 사이)';
    $lang->about_project_directory = '프로젝트를 손쉽게 찾아 갈 수 있도록 생성하시려는 프로젝트의 디렉토리를 설정해주세요.<br />프로젝트가 쉽게 찾아지고 활용될 수 있는데 매우 중요한 정보입니다';
    $lang->about_project_logo = '프로젝트 상단 또는 프로젝트 전체 목록에서 나타나는 로고 이미지를 입력해주세요.';
    $lang->about_project_banner = '請上傳 project 初始畫面所顯示的廣告圖片。(寬度 180px)';

    $lang->msg_wait_repos_create = "저장소 저장소는 즉시 생성되지 않습니다.\n\n문의 사항이 있으시면 사이트 운영자에게 문의 바랍니다";
    $lang->msg_need_one_group = '커밋 허용 그룹은 1개 이상 지정되어 있어야 합니다';
    $lang->msg_module_count_exceed = '허용된 모듈의 개수를 초과하였기에 생성할 수 없습니다';
    $lang->msg_not_enabled_id = '사용할 수 없는 아이디입니다';
    $lang->msg_wrong_project_id = '프로젝트 접속 아이디는 4~12자 사이이며 첫글자는 영문으로, 두번 글자부터는 영문과 숫자만 쓸 수 있습니다';
    $lang->msg_same_site = '동일한 가상 사이트의 모듈은 이동할 수가 없습니다';
    $lang->msg_no_data = '등록된 내용이 없습니다';

    $lang->msg_project_reserved = '프로젝트 생성 요청이 접수되었습니다.\n접수된 내용을 바탕으로 프로젝트 관리자가 심사 후 승인 여부를 결정하게 됩니다.\n감사합니다';

    $lang->confirm_delete_menu_item = '메뉴 항목 삭제시 연결되어 있는 게시판이나 페이지 모듈도 같이 삭제가 됩니다. 그래도 삭제하시겠습니까?';

    $lang->alert_permission_denied_to_create = '沒有建立project權限';
    $lang->alert_project_id_size_is_wrong = 'project ID長度必須在 4-12 個字之間。';
    $lang->alert_project_title_size_is_wrong = '標題長度必須在 4-20 個字之間。';
    $lang->alert_project_description_size_is_wrong = '簡介內容最少輸入10個字最多200個字。';

    $lang->msg_notify_reserved_title = 'project註冊請求';
    $lang->msg_notify_reserved_content = 'project註冊請求。<br/>捷徑 : <a href="%s">%s</a><br/>';
	$lang->date_opened = "建立日期";
	$lang->write_to_forum = "新主題";
	$lang->write_new_issue = "新問題";
	$lang->member_unit = "位";
	$lang->profile = "個人資料";
	$lang->my_projects = "My Projects";
	$lang->contributor_sort_order = array( "points" => "공헌지수 순", "articles" => "글 순", "comments" => "댓글 순", "commits" => "커밋 순" );
	$lang->mysummary_sort_order = array( "point" => "공헌지수 순", "member_count" => "멤버수 순", "regdate" => "개설일 최신 순", "joindate" => "가입일 최신 순");
	$lang->summary_sort_order = array( "point" => "활성화지수 순", "member_count" => "멤버수 순", "regdate" => "개설일 최신 순" );
	$lang->download_sort_order = array( "download_count" => "다운로드 순", "regdate" => "등록일 최신 순", "oldregdate" => "등록일 오랜 순");
	$lang->projectmilestone = "Milestone";
	$lang->project_title = "Project 標題";
	$lang->my_activities = "My Activities";
	$lang->project_setting = "Project 設定";
	$lang->download_program = "程式下載";

	$lang->activity_types = array("d" => "글", "c" => "댓글", "s" => "커밋", "a" => "할당된 이슈");
?>
