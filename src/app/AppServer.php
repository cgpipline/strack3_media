<?php

namespace app;

use Server\Components\Blade\Blade;
use Server\CoreBase\HttpInput;
use Server\CoreBase\Loader;
use Server\SwooleDistributedServer;
use Server\Components\Process\ProcessManager;
use app\Process\MediaAmqpProcess;

/**
 * Created by PhpStorm.
 * User: zhangjincheng
 * Date: 16-9-19
 * Time: 下午2:36
 */
class AppServer extends SwooleDistributedServer
{
    /**
     * 可以在这里自定义Loader，但必须是ILoader接口
     * AppServer constructor.
     * @throws \Noodlehaus\Exception\EmptyDirectoryException
     */
    public function __construct()
    {
        $this->setLoader(new Loader());
        parent::__construct();
    }

    /**
     * 开服初始化(支持协程)
     * @return mixed
     */
    public function onOpenServiceInitialization()
    {
        parent::onOpenServiceInitialization();
    }

    /**
     * 这里可以进行额外的异步连接池，比如另一组redis/mysql连接
     * @param $workerId
     * @return void
     * @throws \Server\CoreBase\SwooleException
     * @throws \Exception
     */
    public function initAsynPools($workerId)
    {
        parent::initAsynPools($workerId);
    }

    /**
     * 用户进程
     * @throws \Exception
     */
    public function startProcess()
    {
        parent::startProcess();

        // 注册 media amqp 队列进程
        ProcessManager::getInstance()->addProcess(MediaAmqpProcess::class,true);
    }

    /**
     * 可以在这验证WebSocket连接,return true代表可以握手，false代表拒绝
     * @param HttpInput $httpInput
     * @return bool
     */
    public function onWebSocketHandCheck(HttpInput $httpInput)
    {
        return true;
    }

    /**
     * @return string
     */
    public function getCloseMethodName()
    {
        return 'onClose';
    }

    /**
     * @return string
     */
    public function getEventControllerName()
    {
        return 'AppController';
    }

    /**
     * @return string
     */
    public function getConnectMethodName()
    {
        return 'onConnect';
    }

    /**
     * 设置模板引擎
     */
    public function setTemplateEngine()
    {
        $this->templateEngine = new Blade($this->cachePath);
        $this->templateEngine->addNamespace("server", SERVER_DIR . '/Views');
        $this->templateEngine->addNamespace("app", APP_DIR. '/Views');
        $this->templateEngine->addExtension('tpl','blade');
    }
}
