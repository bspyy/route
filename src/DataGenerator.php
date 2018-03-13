<?php
namespace SF\Route;

interface DataGenerator
{
    public function addRoute($httpMethod, $routeData, $handler);

    public function getData();
}