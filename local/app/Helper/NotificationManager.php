<?php
/**
 * Created by PhpStorm.
 * User: shuvo
 * Date: 9/18/2017
 * Time: 3:57 PM
 */

namespace App\Helper;


use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
class NotificationManager implements MessageComponentInterface
{


    private $connections;
    private $users = [];
    private $uid = [];
    /**
     * When a new connection is opened it will be passed to this method
     * @param  ConnectionInterface $conn The socket/connection that just connected to your application
     * @throws \Exception
     */
    function __construct()
    {
        $this->connections = new Collection();
    }

    function onOpen(ConnectionInterface $conn)
    {
        // TODO: Implement onOpen() method.
        if(!$this->connections->search($conn)) $this->connections->put($conn->resourceId,$conn);
        Log::info("connection open....".$conn->resourceId." total connection ".$this->connections->count());
    }

    /**
     * This is called before or after a socket is closed (depends on how it's closed).  SendMessage to $conn will not result in an error if it has already been closed.
     * @param  ConnectionInterface $conn The socket/connection that is closing/closed
     * @throws \Exception
     */
    function onClose(ConnectionInterface $conn)
    {
        // TODO: Implement onClose() method.
        if($this->connections->search($conn->resourceId))$this->connections->forget($conn->resourceId);
        Log::info("connection close....".$conn->resourceId);
    }

    /**
     * If there is an error with one of the sockets, or somewhere in the application where an Exception is thrown,
     * the Exception is sent back down the stack, handled by the Server and bubbled back up the application through this method
     * @param  ConnectionInterface $conn
     * @param  \Exception $e
     * @throws \Exception
     */
    function onError(ConnectionInterface $conn, \Exception $e)
    {
        // TODO: Implement onError() method.
        Log::info($e->getMessage());
    }

    /**
     * Triggered when a client sends data through the socket
     * @param  \Ratchet\ConnectionInterface $from The socket/connection that sent the message to your application
     * @param  string $msg The message received
     * @throws \Exception
     */
    function onMessage(ConnectionInterface $from, $msg)
    {
        // TODO: Implement onMessage() method.
        Log::info($msg);
        try{

            $data = json_decode($msg,true);

            $key = 'user';
            if(isset($data[$key])) {
                if($data[$key]=='server'){
                    foreach ($this->users as $k=>$v){
                        if($data['to']==$v){
                            $conn = $this->connections->get($k);
                            Log::info($conn?$conn->resourceId:'nnnnn');
                            $this->connections->get($k)->send($data['message']);
                        }
                    }
                    $this->connections->forget($from->resourceId);
                    $from->close();
                }
                else {
                    if(isset($this->uid[$data['uid']])){
                        $conn = $this->connections->get($this->uid[$data['uid']]);
                        if($conn) $conn->close();
                        $this->connections->forget($this->uid[$data['uid']]);
                        unset($this->users[$this->uid[$data['uid']]]);
                    }
                    $this->users[$from->resourceId] = $data[$key];
                    $this->uid[$data['uid']] = $from->resourceId;
                }
            }

        }catch (\Exception $e){
            Log::info($e->getMessage());
        }
    }
}