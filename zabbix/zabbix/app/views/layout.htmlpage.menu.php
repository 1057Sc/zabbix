<?php
/*
** Zabbix
** Copyright (C) 2001-2018 Zabbix SIA
**
** This program is free software; you can redistribute it and/or modify
** it under the terms of the GNU General Public License as published by
** the Free Software Foundation; either version 2 of the License, or
** (at your option) any later version.
**
** This program is distributed in the hope that it will be useful,
** but WITHOUT ANY WARRANTY; without even the implied warranty of
** MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
** GNU General Public License for more details.
**
** You should have received a copy of the GNU General Public License
** along with this program; if not, write to the Free Software
** Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
**/

$icons = (new CList())
	->addClass(ZBX_STYLE_TOP_NAV_ICONS)
	->addItem(
		(new CForm('get', 'search.php'))
			->addItem([
				(new CTextBox('search', '', false, 255))
					->setAttribute('autocomplete', 'off')
					->addClass(ZBX_STYLE_SEARCH),
				(new CSubmitButton(SPACE))->addClass(ZBX_STYLE_BTN_SEARCH)
			])
	);
/*	->addItem(
		(new CLink('Share', ' '))
			->addClass(ZBX_STYLE_TOP_NAV_ZBBSHARE)
			->setAttribute('target', '_blank')
			->setAttribute('title', _('seabox'))
	)
	->addItem(
		(new CLink(SPACE, ' '))
			->addClass(ZBX_STYLE_TOP_NAV_HELP)
			->setAttribute('target', '_blank')
			->setAttribute('title', _('Help'))
	);*/
$zzz=array("admmmin");
if (!$data['user']['is_guest']) {
	$icons->addItem(
		(new CLink(SPACE, 'profile.php'))
			->addClass(ZBX_STYLE_TOP_NAV_PROFILE)
			->setAttribute('title', getUserFullname($data['user'])) //getUserFullname($data['user']
    );
/*        ->addItem((new CList($zzz))->addClass(ZBX_STYLE_TOP_ADMIN)
    )*/

}
//添加用户信息
$test1=array(
    "test"=>array("test1"=>"Admin")
);
//$test2=array(gerRealUsername(getUsername($data['user'])));
//$test3=array(gerRealUsername('admin'));
$test2=array("当前登录用户: ".getrealUsername((getUsername($data['user']))));
$icons->addItem(
    (new Clist($test2))
    //(new CLink('退出', 'index.php?reconnect=1'))
    //(new CLink($test2, 'profile.php'))
        ->addClass(ZBX_STYLE_TOP_ADMIN)
);


$icons->addItem(
	(new CLink(SPACE, 'index.php?reconnect=1'))
		->addClass(ZBX_STYLE_TOP_NAV_SIGNOUT)
		->setAttribute('title', _('Sign out'))
		->addSID()
);

//添加退出信息
$test3=array(
    "test"=>array("test1"=>"退出")
);
$icons->addItem(
    (new CLink('退出', 'index.php?reconnect=1'))
        ->addClass(ZBX_STYLE_TOP_LOGOUT)
        ->addSID()
);

$test=array(
    "test"=>array("test1"=>"统一监控系统")
);
$ctest=array("cteset1">="统一监控系统");
// 1st level menu
$top_menu = (new CDiv())
    ->addItem(
        (new CLink((new CDiv())->addClass(ZBX_STYLE_LOGO), 'zabbix.php?action=dashboard.view'))
            ->addClass(ZBX_STYLE_HEADER_LOGO)
    )
	->addItem(
        //(new CList($test['test']['test1']))
        (new CList($test))
            ->addClass(ZBX_STYLE_HEADER_LOGO_TEXT)
		/*(new CLink((new CDiv())->addClass(ZBX_STYLE_LOGO), 'zabbix.php?action=dashboard.view'))
			->addClass(ZBX_STYLE_HEADER_LOGO)*/
	)
    ->addItem($icons)
    //一级菜单
	->addItem(
		(new CList($data['menu']['main_menu']))
            ->addClass(ZBX_STYLE_TOP_NAV)
       // ->Item((new CList($data['menu']['main_menu']))->addClass(ZBX_STYLE_TOP_NAV))
       //->addItem((new CList($zzz))->addClass(ZBX_STYLE_TOP_ADMIN)
       //->addItem((new CList($zzz)))
	)
	->addItem($icons)
	->addClass(ZBX_STYLE_TOP_NAV_CONTAINER)
	->setId('mmenu');


   $sub_menu_div = (new CDiv())
	->addClass(ZBX_STYLE_TOP_SUBNAV_CONTAINER)
	//->onMouseover()
   ->onMouseover('javascript: MMenu.submenu_mouseOver();')
	->onMouseout('javascript: MMenu.mouseOut();');

// 2nd level menu
foreach ($data['menu']['sub_menus'] as $label => $sub_menu) {
	$sub_menu_row = (new CList())
		->addClass(ZBX_STYLE_TOP_SUBNAV)
		->setId('sub_'.$label);

	foreach ($sub_menu as $id => $sub_page) {
		$url = new CUrl($sub_page['menu_url']);
		if ($sub_page['menu_action'] !== null) {
			$url->setArgument('action', $sub_page['menu_action']);
		}

		$url
			->setArgument('ddreset', 1)
			->removeArgument('sid');

		$sub_menu_item = new CLink($sub_page['menu_text'], $url->getUrl());
		if ($sub_page['selected']) {
			$sub_menu_item->addClass(ZBX_STYLE_SELECTED);
		}

		$sub_menu_row->addItem($sub_menu_item);
	}

	if ($data['menu']['selected'] === $label) {
		$sub_menu_row->setAttribute('style', 'display: block;');
		insert_js('MMenu.def_label = '.zbx_jsvalue($label));
	}
	else {
		$sub_menu_row->setAttribute('style', 'display: none;');
	}
	$sub_menu_div->addItem($sub_menu_row);
}

if ($data['server_name'] !== '') {
	$sub_menu_div->addItem(
		(new CDiv($data['server_name']))->addClass(ZBX_STYLE_SERVER_NAME)
	);
}

(new CTag('header', true))
	->setAttribute('role', 'banner')
	->addItem(
		(new CDiv())
			->addItem($top_menu)
			->addItem($sub_menu_div)
			->addClass(ZBX_STYLE_NAV)
			->setAttribute('role', 'navigation')
	)
	->show();
