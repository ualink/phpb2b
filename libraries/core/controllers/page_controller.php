<?php

class Page extends PbController
{
    public $name = 'Page';

    public function __construct()
    {
    }

    public function index()
    {
        global $smarty, $viewhelper, $tpl_dir, $theme_name;
        $this->loadModel('userpage');
        $smarty->setTemplateDir(PHPB2B_ROOT.$tpl_dir.DS.'site'.DS.$theme_name.DS, 'pages');
        $conditions = [];
        $tpl_file = 'pages/default';
        !empty($_GET) && $_GET = clear_html($_GET);
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $conditions[] = 'id='.$id;
        } elseif (!empty($_GET['name'])) {
            $conditions[] = "name='".trim($_GET['name'])."' OR title='".trim($_GET['name'])."'";
        } elseif (!empty($_GET['title'])) {
            $conditions[] = "title='".trim($_GET['title'])."' OR name='".trim($_GET['title'])."'";
        }
        $this->userpage->setCondition($conditions);
        $result = $this->userpage->dbstuff->GetRow("SELECT * FROM {$this->userpage->table_prefix}userpages ".$this->userpage->getCondition());
        if (!empty($result)) {
            $title = $result['title'];
            $viewhelper->setTitle($title);
            $viewhelper->setPosition($title);
            if (isset($smarty->template_dir[0])) {
                $tple_dir = $smarty->template_dir[0];
            } else {
                $tple_dir = $smarty->template_dir;
            }
            if (!empty($result['templet_name'])) {
                $tpl_file = 'pages/'.$result['templet_name'];
            } elseif ($viewhelper->tpl_exists($tple_dir.'pages/'.$result['name'].$smarty->tpl_ext)) {
                $tpl_file = 'pages/'.$result['name'];
            }
            setvar('item', pb_lang_split_recursive($result));
        } else {
            setvar('item', []);
        }
        $smarty->assign('position', $viewhelper->getPosition());
        $smarty->assign('page_title', $viewhelper->getTitle());
        $smarty->display($tpl_file.$smarty->tpl_ext);
    }
}
