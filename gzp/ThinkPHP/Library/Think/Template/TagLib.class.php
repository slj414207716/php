<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

namespace Think\Template;

/**
 * ThinkPHP标签库TagLib解析基类
 */
class TagLib
{

	/**
	 * 标签库定义XML文件
	 *
	 * @var string
	 * @access protected
	 */
	protected $xml  = '';
	protected $tags = array();// 标签定义
	/**
	 * 标签库名称
	 *
	 * @var string
	 * @access protected
	 */
	protected $tagLib = '';

	/**
	 * 标签库标签列表
	 *
	 * @var string
	 * @access protected
	 */
	protected $tagList = array();

	/**
	 * 标签库分析数组
	 *
	 * @var string
	 * @access protected
	 */
	protected $parse = array();

	/**
	 * 标签库是否有效
	 *
	 * @var string
	 * @access protected
	 */
	protected $valid = false;

	/**
	 * 当前模板对象
	 *
	 * @var object
	 * @access protected
	 */
	protected $tpl;

	protected $comparison = array(
		' nheq ' => ' !== ',
		' heq '  => ' === ',
		' neq '  => ' != ',
		' eq '   => ' == ',
		' egt '  => ' >= ',
		' gte '  => ' >= ',
		' gt '   => ' > ',
		' elt '  => ' <= ',
		' lte '  => ' <= ',
		' lt '   => ' < ',
	);

	/**
	 * 架构函数
	 *
	 * @access public
	 */
	public function __construct()
	{
		$this->tagLib = strtolower(substr(get_class($this), 6));
		$this->tpl    = \Think\Think::instance('Think\\Template');
	}

	/**
	 * TagLib标签属性分析 返回标签属性数组
	 *
	 * @access public
	 * @param $attr
	 * @param $tag
	 * @return array
	 * @throws \Think\Exception
	 */
	public function parseXmlAttr($attr, $tag)
	{
		//XML解析安全过滤
		$array   = array();
		$pattern = '/(?:\s+\w+(?:\s*=\s*(?:(?:"[^"]*")|(?:\'[^\']*\')|[^\s]+))?)/';
		if (preg_match_all($pattern, $attr, $match)) {
			$tag  = strtolower($tag);
			$item = $this->tags[$tag];
			if (!isset($this->tags[$tag])) {
				// 检测是否存在别名定义
				foreach ($this->tags as $key => $val) {
					if (isset($val['alias']) && in_array($tag, explode(',', $val['alias']))) {
						$item = $val;
						break;
					}
				}
			}
			$attrs = explode(',', $item['attr']);
			if (isset($item['must'])) {
				$must = explode(',', $item['must']);
			} else {
				$must = array();
			}

			foreach ($match[0] as $v) {
				list($name, $value) = array_map('trim', explode('=', $v, 2));
				if (in_array($name, $attrs)) {
					$array[$name] = preg_replace('/(^["\']|["\']$)/', '', $value);
				} else if (in_array($name, $must)) {
					E(L('_PARAM_ERROR_') . ':' . $name);
				}
			}
		}
		return $array;
	}

	/**
	 * parse expression
	 *
	 * @param $name
	 * @return string
	 */
	public function parseExpression(&$name)
	{
		$parseStr = '<?php ';
		if (0 === strpos($name, ':')) {
			// DADDY : allow $array.key syntax
			$exp  = $this->parseCondition(substr($name, 1));
			$name = '$_' . uniqid('');
			$parseStr .= $name . '=' . $exp . ';';
		} else {
			$name = $this->autoBuildVar($name);
		}
		$parseStr .= '?>';
		return $parseStr;
	}

	/**
	 * 解析条件表达式
	 *
	 * @access public
	 * @param string $condition 表达式标签内容
	 * @return array
	 */
	public function parseCondition($condition)
	{
		$condition = str_ireplace(array_keys($this->comparison), array_values($this->comparison), $condition);
		$condition = $this->tpl->parseDotVar($condition);
		return $condition;
	}

	/**
	 * 自动识别构建变量
	 *
	 * @access public
	 * @param string $name 变量描述
	 * @return string
	 */
	public function autoBuildVar($name)
	{
		$varArray = explode('|', $name);
		$name     = array_shift($varArray);

		// DADDY : allow name="$var" syntax
		if ('$' == substr($name, 0, 1)) {
			$name = substr($name, 1);
		}

        // $Think
		if ('Think.' == substr($name, 0, 6)) {
			return $this->parseThinkVar($name);
		}
        // object
        if (strpos($name, ':')) {
			$name = preg_replace('/([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*):([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)/', '$1->$2', $name);
		}
        // $var.key1.key2...
		if (strpos($name, '.')) {
            $var_identity = strtolower(C('TMPL_VAR_IDENTIFY'));
            if ($var_identity == 'obj') {
                $name = str_replace('.', '->', $name);
            } else {
                // default array, not use auto detect
                $name = preg_replace('/\.([^\$\.\[\]\s]+)/', "['$1']", $name);
            }
        }
		// check if constant
		if (!defined($name)) {
			$name = '$' . $name;
		}

		if (count($varArray) > 0) {
			$name = $this->tpl->parseVarFunction($name, $varArray);
		}

		return $name;
	}

	/**
	 * 用于标签属性里面的特殊模板变量解析
	 * 格式 以 Think. 打头的变量属于特殊模板变量
	 *
	 * @access public
	 * @param string $varStr 变量字符串
	 * @return string
	 */
	public function parseThinkVar($varStr)
	{
		if (is_array($varStr)) {//用于正则替换回调函数
			$varStr = $varStr[1];
		}
		$vars     = explode('.', $varStr);
		$vars[1]  = strtoupper(trim($vars[1]));
		$parseStr = '';
		if (count($vars) >= 3) {
			$vars[2] = trim($vars[2]);
			switch ($vars[1]) {
				case 'SERVER':
					$parseStr = '$_SERVER[\'' . $vars[2] . '\']';
					break;
				case 'GET':
					$parseStr = '$_GET[\'' . $vars[2] . '\']';
					break;
				case 'POST':
					$parseStr = '$_POST[\'' . $vars[2] . '\']';
					break;
				case 'COOKIE':
					if (isset($vars[3])) {
						$parseStr = '$_COOKIE[\'' . $vars[2] . '\'][\'' . $vars[3] . '\']';
					} elseif (C('COOKIE_PREFIX')) {
						$parseStr = '$_COOKIE[\'' . C('COOKIE_PREFIX') . $vars[2] . '\']';
					} else {
						$parseStr = '$_COOKIE[\'' . $vars[2] . '\']';
					}
					break;
				case 'SESSION':
					if (isset($vars[3])) {
						$parseStr = '$_SESSION[\'' . $vars[2] . '\'][\'' . $vars[3] . '\']';
					} elseif (C('SESSION_PREFIX')) {
						$parseStr = '$_SESSION[\'' . C('SESSION_PREFIX') . '\'][\'' . $vars[2] . '\']';
					} else {
						$parseStr = '$_SESSION[\'' . $vars[2] . '\']';
					}
					break;
				case 'ENV':
					$parseStr = '$_ENV[\'' . $vars[2] . '\']';
					break;
				case 'REQUEST':
					$parseStr = '$_REQUEST[\'' . $vars[2] . '\']';
					break;
				case 'CONST':
					$parseStr = strtoupper($vars[2]);
					break;
				case 'LANG':
					$parseStr = 'L("' . $vars[2] . '")';
					break;
				case 'CONFIG':
					$parseStr = 'C("' . $vars[2] . '")';
					break;
			}
		} else if (count($vars) == 2) {
			switch ($vars[1]) {
				case 'NOW':
					$parseStr = "date('Y-m-d g:i a',time())";
					break;
				case 'VERSION':
					$parseStr = 'THINK_VERSION';
					break;
				case 'TEMPLATE':
					$parseStr = 'C("TEMPLATE_NAME")';
					break;
				case 'LDELIM':
					$parseStr = 'C("TMPL_L_DELIM")';
					break;
				case 'RDELIM':
					$parseStr = 'C("TMPL_R_DELIM")';
					break;
				default:
					if (defined($vars[1])) $parseStr = $vars[1];
			}
		}
		return $parseStr;
	}

	// 获取标签定义
	public function getTags()
	{
		return $this->tags;
	}
}