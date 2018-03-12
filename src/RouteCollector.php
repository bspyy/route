<?php
namespace SF;

class RouteCollector
{
    protected $currentGroupPrefix;

    protected $dataGenerator;

    protected $routeData;

    protected $routeParser;

    public function __construct($routeData = [])
    {
        $this->routeData = $routeData ? $routeData : [];

        $this->routeParser = new RouteParser();

        $this->dataGenerator = new DataGenerator();
    }

    public function addRoute($httpMethod,$route,$handler)
    {
        $route = $this->currentGroupPrefix . $route;
        
        $routeDatas = $this->routeParser->parse($route);        
        foreach ((array)$httpMethod as $method){
            foreach ($routeDatas as $routeData) {
                $this->dataGenerator->addRoute($method, $routeData, $handler);
            }
        }



      /*  if($this->isStaticRoute($route)){
            //需通过正则匹配的路由
            $routeData = isset($this->routeData[1][strtoupper($httpMethod)]) ? $this->routeData[1][strtoupper($method)] : [
                'regex' => '',
                'routeMap' => []
            ];

            $routeDatas = $this->routeParser->parse($route);
            $routeParams = [];
            $routeRegex = '';

            foreach ($routeDatas[count($routeDatas) - 1] as $data){
                if(is_array($data)){
                    $routeParams[$data[0]] = $data[0];
                    $routeRegex .= '('.$data[1].')';
                }else{
                    $routeRegex .= $data;
                }
            }

            $routeData['routeMap'][] = [$handler,$routeParams];
            $this->routeData[1][strtoupper($method)] = [
                'regex' => $routeData['regex'].'|'.$routeRegex,
                'routeMap' => $routeData['routeMap']
            ];
        }else{
            //纯字符串路由
            $this->routeData[0][strtoupper($httpMethod)][$route] = $handler;
        }*//*  if($this->isStaticRoute($route)){
            //需通过正则匹配的路由
            $routeData = isset($this->routeData[1][strtoupper($httpMethod)]) ? $this->routeData[1][strtoupper($method)] : [
                'regex' => '',
                'routeMap' => []
            ];

            $routeDatas = $this->routeParser->parse($route);
            $routeParams = [];
            $routeRegex = '';

            foreach ($routeDatas[count($routeDatas) - 1] as $data){
                if(is_array($data)){
                    $routeParams[$data[0]] = $data[0];
                    $routeRegex .= '('.$data[1].')';
                }else{
                    $routeRegex .= $data;
                }
            }

            $routeData['routeMap'][] = [$handler,$routeParams];
            $this->routeData[1][strtoupper($method)] = [
                'regex' => $routeData['regex'].'|'.$routeRegex,
                'routeMap' => $routeData['routeMap']
            ];
        }else{
            //纯字符串路由
            $this->routeData[0][strtoupper($httpMethod)][$route] = $handler;
        }*/
    }

    public function addGroup($var = [],callable $callable)
    {

    }
    
    public function getData()
    {
        return $this->dataGenerator->getData();
    }
}