<?php
namespace SF;

class DataGenerator
{

    protected $staticRoutes = [];

    protected $methodToRegexToRoutesMap = [];


    public function __construct()
    {
        $this->routeParser = new RouteParser();
    }

    protected function isStaticRoute($routeData)
    {
        return count($routeData) === 1 && is_string($routeData[0]);
    }

    public function addRoute($httpMethod,$routeData,$handler)
    {
        if($this->isStaticRoute($routeData)){
            $this->addStaticRoute($httpMethod,$routeData,$handler);
        }else{
            $this->addVariableRoute($httpMethod,$routeData,$handler);
        }
    }

    public function addStaticRoute($httpMethod,$routeData,$handler)
    {
        $routeStr = $routeData[0];
        $this->staticRoutes[strtoupper($httpMethod)][$routeStr] = $handler;
    }

    public function addVariableRoute($httpMethod,$routeData,$handler)
    {
        list($regex, $variables) = $this->buildRegexForRoute($routeData);

        $routeMap = isset($this->methodToRegexToRoutesMap[$httpMethod]['routeMap']) ? $this->methodToRegexToRoutesMap[$httpMethod]['routeMap'] : [];

        $routeCount = count($routeMap);
        $regex .= str_repeat('&',$routeCount);

        if(!isset($this->methodToRegexToRoutesMap[$httpMethod]['regex'])){
            $this->methodToRegexToRoutesMap[$httpMethod]['regex'] = $regex;
        }else{
            $this->methodToRegexToRoutesMap[$httpMethod]['regex'] .= '|'.$regex;
        }

        $this->methodToRegexToRoutesMap[$httpMethod]['routeMap'][$routeCount] = [
            $handler, $variables
        ];
    }

    private function buildRegexForRoute($routeData)
    {
        $regex = '';
        $variables = [];
        foreach ($routeData as $part) {
            if (is_string($part)) {
                $regex .= preg_quote($part, '~');
                continue;
            }

            list($varName, $regexPart) = $part;

            if (isset($variables[$varName])) {
                throw new BadRouteException(sprintf(
                    'Cannot use the same placeholder "%s" twice', $varName
                ));
            }

            if ($this->regexHasCapturingGroups($regexPart)) {
                throw new BadRouteException(sprintf(
                    'Regex "%s" for parameter "%s" contains a capturing group',
                    $regexPart, $varName
                ));
            }

            $variables[$varName] = $varName;
            $regex .= '(' . $regexPart . ')';
        }

        return [$regex, $variables];
    }

    /**
     * @param string
     * @return bool
     */
    private function regexHasCapturingGroups($regex)
    {
        if (false === strpos($regex, '(')) {
            // Needs to have at least a ( to contain a capturing group
            return false;
        }

        // Semi-accurate detection for capturing groups
        return (bool) preg_match(
            '~
                (?:
                    \(\?\(
                  | \[ [^\]\\\\]* (?: \\\\ . [^\]\\\\]* )* \]
                  | \\\\ .
                ) (*SKIP)(*FAIL) |
                \(
                (?!
                    \? (?! <(?![!=]) | P< | \' )
                  | \*
                )
            ~x',
            $regex
        );
    }

    public function getData()
    {
        return [$this->staticRoutes,$this->methodToRegexToRoutesMap];
    }
}
