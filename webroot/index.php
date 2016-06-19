<?php

require dirname(__DIR__) . '/config/bootstrap.php';        //dirname返回路径中的目录部分

//路由解析
use Cake\Network\Request;
use Cake\Network\Response;
use Cake\Routing\DispatcherFactory;

$dispatcher = DispatcherFactory::create();      //抽修工厂?
$dispatcher->dispatch(
    Request::createFromGlobals(),
    new Response()
);
?>