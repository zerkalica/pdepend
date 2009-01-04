<?php
/**
 * This file is part of PHP_Reflection.
 * 
 * PHP Version 5
 *
 * Copyright (c) 2008-2009, Manuel Pichler <mapi@pdepend.org>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Manuel Pichler nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   PHP
 * @package    PHP_Reflection
 * @subpackage AST
 * @author     Manuel Pichler <mapi@pdepend.org>
 * @copyright  2008-2009 Manuel Pichler. All rights reserved.
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id$
 * @link       http://www.manuel-pichler.de/
 */

require_once 'PHP/Reflection/AST/AbstractNode.php';

/**
 * This class provides an interface to a single source file.
 *
 * @category   PHP
 * @package    PHP_Reflection
 * @subpackage AST
 * @author     Manuel Pichler <mapi@pdepend.org>
 * @copyright  2008-2009 Manuel Pichler. All rights reserved.
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: @package_version@
 * @link       http://www.manuel-pichler.de/
 */
class PHP_Reflection_AST_File extends PHP_Reflection_AST_AbstractNode
{
    /**
     * The source file name/path.
     *
     * @var string $_fileName
     */
    private $_fileName = null;
    
    /**
     * Normalized code in this file.
     *
     * @var string $_source
     */
    private $_source = null;
    
    /**
     * The lines of code in this file.
     *
     * @var array(integer=>string) $_loc
     */
    private $_loc = null;
    
    /**
     * The tokens for this file.
     *
     * @var array(array) $_tokens
     */
    private $_tokens = array();
    
    /**
     * The comment for this type.
     *
     * @var string $_docComment
     */
    private $_docComment = null;
    
    /**
     * Constructs a new source file instance.
     *
     * @param string $fileName The source file name/path.
     */
    public function __construct($fileName)
    {
        parent::__construct('#file');
        
        if ($fileName !== null) {
            $this->_fileName = realpath($fileName);
        }
    }
    
    /**
     * Returns the physical file name for this object.
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->_fileName;
    }
    
    /**
     * Returns the lines of code with stripped whitespaces.
     *
     * @return array(integer=>string)
     */
    public function getLoc()
    {
        $this->readSource();
        return $this->_loc;
    }
    
    /**
     * Returns normalized source code with stripped whitespaces.
     *
     * @return array(integer=>string)
     */
    public function getSource()
    {
        $this->readSource();
        return $this->_source;
    }
    
    /**
     * Returns an <b>array</b> with all tokens within this file.
     *
     * @return array(array)
     */
    public function getTokens()
    {
        return $this->_tokens;
    }
    
    /**
     * Sets the tokens for this file.
     *
     * @param array(array) $tokens The generated tokens.
     * 
     * @return void
     */
    public function setTokens(array $tokens)
    {
        $this->_tokens = $tokens;
    }
    
    /**
     * Returns the doc comment for this item or <b>null</b>.
     *
     * @return string
     */
    public function getDocComment()
    {
        return $this->_docComment;
    }
    
    /**
     * Sets the doc comment for this item.
     *
     * @param string $docComment The doc comment block.
     * 
     * @return void
     */
    public function setDocComment($docComment)
    {
        $this->_docComment = $docComment;
    }
    
    /**
     * Visitor method for node tree traversal.
     *
     * @param PHP_Reflection_VisitorI $visitor The context visitor implementation.
     * 
     * @return void
     */
    public function accept(PHP_Reflection_VisitorI $visitor)
    {
        $visitor->visitFile($this);
    }
    
    /**
     * Returns the string representation of this class.
     *
     * @return string
     */
    public function __toString()
    {
        return ($this->_fileName === null ? '' : $this->_fileName);
    }
    
    /**
     * Reads the source file if required.
     *
     * @return void
     */
    protected function readSource()
    {
        if ($this->_loc === null) {
            if (is_file($this->_fileName) === true) {
                $source        = file_get_contents($this->_fileName);
                $this->_loc    = preg_split('#(\r\n|\n|\r)#', $source);
                $this->_source = implode("\n", $this->_loc);
            } else {
                $this->_loc    = array();
                $this->_source = '';
            }
        }
    }
}