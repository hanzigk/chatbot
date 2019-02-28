<?php
/**
 * Created by IntelliJ IDEA.
 * User: Nathan
 * Date: 2/27/2019
 * Time: 2:04 PM
 */

namespace App\Controller;

use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChatBotController extends AbstractController
{
    public function indexAction()
    {
        return $this->render('chatbot/index.html.twig');
    }

    public function queryAction(Request $request)
    {
        $q = $request->get('input');
        $opts = array(
            'http'=>array(
                'method'=>"GET",
                'timeout'=>60,

            )
        );
        $context = stream_context_create($opts);
        $clientIp = $request->getClientIp();

        $newurl="127.0.0.1:8765/?q=" . urlencode($q) . "&clientIp=". $clientIp;
        $ch=curl_init($newurl);
        curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);

        //$response = file_get_contents($newurl, false, $context);
        $response=curl_exec($ch);
        $res = json_decode($response, true);
        $total = $res['total'];
        $result = '';
        if ($total > 0) {
            $result = $res['result'][0]['answer'];
        }
        return new Response($result);
    }
}