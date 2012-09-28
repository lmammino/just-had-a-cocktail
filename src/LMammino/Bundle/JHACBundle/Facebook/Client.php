<?php

namespace LMammino\Bundle\JHACBundle\Facebook;

use Buzz\Client\Curl as Buzz;
use Buzz\Message\Request;
use Buzz\Message\Response;
use Buzz\Message\Form\FormRequest;

class Client
{

    public static $GRAPH_HOST = 'https://graph.facebook.com/';

    /**
     * @var Buzz
     */
    protected $buzz;

    protected $accessToken;

    public function __construct($accessToken = NULL)
    {
        $this->accessToken = $accessToken;
        $this->buzz = new Buzz();
        $this->buzz->setTimeout(15000);
    }

    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @return \Buzz\Client\Curl
     */
    public function getBuzz()
    {
        return $this->buzz;
    }

    /**
     * Performs an "Had" action on facebook app
     *
     * <code>
     * curl
     *  -F 'access_token=xxxxxxxxxxxxxxxx'
     *  -F 'cocktail=http://just-had-a-cocktail.pagodabox.com/cocktail/yyyyyyy'
     * 'https://graph.facebook.com/me/lmammino-jhac:had'
     * </code>
     *
     * @param $cocktailUrl
     */
    public function hadCocktail($cocktailUrl, $timestamp = NULL)
    {
        $request = new FormRequest(Request::METHOD_POST, 'me/lmammino-jhac:had', self::$GRAPH_HOST);
        $request->setField('access_token', $this->accessToken);
        $request->setField('cocktail', $cocktailUrl);
        if($timestamp)
        {
            $date = date('c', $timestamp);
            $request->setField('start_time', $date);
            $request->setField('end_time', $date);
        }

        $response = new Response();

        $this->buzz->send($request, $response);

        return json_decode($response->getContent());
    }


}
