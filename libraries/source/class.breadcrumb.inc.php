<?php
class breadcrumb {
 /** 
  * Set Root Index Page
  * 
  * Sets the root index page link for those who have a splash page and do not
  * want the home breadcrumb to take them to the splashpage.
  * <code>$breadcrumb->rootIndexLink = 'index2.php';</code>
  * @var string
  * @since Version 2.4.4
  */
  var $rootIndexLink = '';
  
 /** 
  * Homepage naming
  * 
  * A person can use any name for the homepage/base directory or not show it at
  * all.
  * <code>$breadcrumb->homepage = 'homepage';</code><br>
  * <b>Example:</b>
  * <samp>homepage > Baskettcase > php_classes > breadcrumb > index.htm</samp>
  * <br><br><code>$breadcrumb->homepage = '';</code><br>
  * <b>Example:</b>
  * <samp>Baskettcase > php_classes > breadcrumb > index.htm</samp>
  * @var string
  * @since Version 1.0.0
  */
  var $homepage = 'home';
  
 /** 
  * Case formatting
  * 
  * Specify the format you would like the directory names to be in, first 
  * letters uppercase, all uppercase, all lowercase, or the actual naming of
  * your directory with no changes.
  * - ucwords = uppers case words (use with _toSpace)
  * - titlecase = upper case words except small words (the, is, with, etc)
  * - ucfirst = upper case first letter
  * - uppercase = all uppercase
  * - lowercase = all lowercase
  * - none = show directories as they are named in path structure (DEFAULT)
  *
  * <code>$breadcrumb->dirformat = 'ucfirst';</code><br>
  * <b>Example:</b>
  * <samp>Baskettcase > Php_classes > Breadcrumb > Index.htm</samp>
  * @var string ucfirst, uppercase, lowercase, ucwords, titlecase
  * @since Version 1.0.0
  */
  var $dirformat = '';
  
 /** 
  * Symbol separator
  * 
  * Specify what symbols to use between your directory names. 
  * <pre>DEFAULT = ' > '</pre>
  * <code>$breadcrumb->symbol = ' || ';</code><br>
  * <b>Example:</b>
  * <samp>Baskettcase || php_classes || breadcrumb || index.htm</samp>
  * @var string
  * @since Version 1.0.0
  */
  var $symbol = ' &gt; ';
  
 /** 
  * CSS Class Style
  * 
  * Use a css class to define the look of your breadcrumb.
  * <code>$breadcrumb->cssClass = 'crumb';</code><br>
  * @var string
  * @since Version 2.3.0
  */
  var $cssClass = '';
  
 /** 
  * Special formatting
  * 
  * I also added a "special" formatting which allows you to show the path as if
  * Elmer Fudd wrote it, or a Hacker wrote it, or in Reverse or hey even in pig
  * latin!
  * - elmer = elmer fudd translation
  * - hacker = hacker speach translation
  * - pig = pig latin translation
  * - reverse = Reverses the text so it is backwards
  * - none = no special formatting (DEFAULT)
  *
  * <code>$breadcrumb->special = 'elmer';</code><br>
  * <b>Example:</b>
  * <samp>Baskettcase > php_cwasses > bweadcwumb > index.htm</samp>
  * @var string elmer, hacker, pig, reverse, none
  * @since Version 1.0.0
  */
  var $special = '';
  
 /** 
  * Frameset Target
  * 
  * Target a frameset.
  * <code>$breadcrumb->target = '_blank';</code>
  * @var string
  * @since Version 2.4.0
  */
  var $target = '';
  
 /** 
  * Show filename
  * 
  * Specify whether or not to show the current file name, just show the path or
  * show the path with the file name.
  * <code>$breadcrumb->showfile = FALSE;</code><br>
  * <b>Example:</b>
  * <samp>homepage > Baskettcase > php_classes > breadcrumb</samp>
  * <br><br><code>$breadcrumb->showfile = TRUE;</code><br>
  * <b>Example:</b>
  * <samp>Baskettcase > php_classes > breadcrumb > index.htm</samp>
  * @var bool
  * @since Version 1.0.0
  */
  var $showfile = TRUE;
  
 /** 
  * Unlink Current Directory
  * 
  * Removes the current directory link.
  * <code>$breadcrumb->unlinkCurrentDir = TRUE;</code>
  * @var bool
  */
  var $unlinkCurrentDir = FALSE;
  
 /** 
  * Hide File Extension
  * 
  * Hides the filename extension
  * <code>$breadcrumb->hideFileExt = TRUE;</code><br>
  * <b>Example:</b>
  * <samp>Baskettcase > php_classes > breadcrumb > index</samp>
  * @var bool
  * @since Version 2.4.2
  */
  var $hideFileExt = FALSE;
  
 /** 
  * Filename Linking
  * 
  * Links the filename to itself.
  * <code>$breadcrumb->linkFile = TRUE;</code>
  * @var bool
  * @since Version 2.4.0
  */
  var $linkFile = FALSE;
  
 /** 
  * Replace Underscores
  * 
  * Replace underscores with spaces. 
  * <code>$breadcrumb->_toSpace = TRUE;</code><br>
  * <b>Example:</b>
  * <samp>Baskettcase > php classes > breadcrumb > index.htm</samp>
  * @link http://www.baskettcase.com/classes/breadcrumb/test_dir_with_underscores/underscore.php
  * @var bool
  * @since Version 2.3.0
  */
  var $_toSpace = FALSE;
  
 /** 
  * Use images
  * 
  * Use images in place of text for your breadcrumbs, by specifying the
  * directory the images can be found in. You can also specify the image type
  * (gif, jpg, etc), border, id, name, hspace, vspace, align, height, width, and
  * alt attributes. I have also included an example of how to use an image for
  * the separator character. If you use the changeName function along with 
  * images, the alt attribute will be the changed name, while the id and name
  * attributes will remain the actual directory name.
  * <code>$breadcrumb->imagedir = array('path'=>'images/', 'type'=>'gif', 'border'=>2, 'id'=>FALSE, 'name'=>TRUE,
  * 'hspace'=>2, 'vspace'=>4, 'align'=>'top', 'height'=>20, 'width'=>75, 'alt'=>TRUE, 'title'=>TRUE);</code>
  * @var array path, type, border, id, name, hspace, vspace, align, height, width, alt, title
  * @since Version 2.0.0
  */
  var $imagedir = array();
  
 /** 
  * Directory aliasing
  * 
  * Rename your directories to whatever you would like them to show up as in
  * your breadcrumb.
  * <code>$breadcrumb->changeName=array('home'=>'Baskettcase Homepage',
  *                              'php_classes'=>'PHP Classes',
  *                              'breadcrumb'=>'Breadcrumbs Class');</code><br>
  * <b>Example:</b>
  * <samp>Baskettcase Homepage > PHP Classes > Breadcrumbs Class > index.htm</samp>
  * @var array
  * @since Version 1.0.0
  */
  var $changeName = array();
  
 /** 
  * Filename Aliasing
  * 
  * Change the filename to a more user friendly one.
  * <code>$breadcrumb->changeFileName = array('/classes/breadcrumb/index.htm'=>'Breadcrumbs PHP Class v. 2.4.4');</code><br>
  * <b>Example:</b>
  * <samp>Baskettcase > php_classes > breadcrumb > Breadcrumbs PHP Class v. 2.4.4</samp>
  * @var array
  * @since Version 2.3.7
  */
  var $changeFileName = array();
  
 /** 
  * Index exists?
  * 
  * <p>Set your index file name so that if the file exists then link the
  * directory, otherwise if the file does not exist do not create a link.</p>
  * <p>This is good for those people that do not want surfers to be able to look
  * at their directory structure if they do not have a default index page within
  * that directory. It will still show the directory name within the breadcrumb,
  * but it will not add a link to the directory name.</p>
  * <code>$breadcrumb->fileExists = array('index.htm','index.php','index.html');</code>
  * @var array
  * @since Version 1.0.0
  */
  var $fileExists = array();
  
 /** 
  * Remove Directories
  * 
  * Hide a directory from showing in the breadcrumb.
  * <code>$breadcrumb->removeDirs = array('php_classes');</code><br>
  * <b>Example:</b>
  * <samp>homepage > Baskettcase > breadcrumb > index.htm</samp>
  * @var array
  * @since Version 2.3.7
  */
  var $removeDirs = array();

 /** 
  * Directory Structure
  * @access private
  */
  var $scriptArray = '';
  
 /** 
  * File name
  * @access private
  */
  var $fileName = '';
  
 /** 
  * Document Root
  * @access private
  */
  var $document_root = '';
  
 /** 
  * is this a personal site?
  * @access private
  */
  var $personalSite = '';
  
 /** 
  * Show errors
  * @access private
  */
  var $showErrors = FALSE;
  
  /**
   * Breadcrumb
   * @since Version 2.0.0
   * @access private
   */
  function breadcrumb() {
    // Creates an array of Directory Structure
    $this->scriptArray = explode("/", pb_getenv('PHP_SELF'));
    // Pops the filename off the end and throws it into it's own variable
    $this->fileName = array_pop($this->scriptArray);
    // Is this a personal site?
    if (substr($_SERVER['PHP_SELF'], 1, 1)=='~') {
    	$tmp = explode('/', pb_getenv('PHP_SELF'));
    	$this->personalSite = $tmp[1];
			$this->document_root = str_replace(str_replace('/'.$this->personalSite, '', pb_getenv("SCRIPT_NAME")), '', $_SERVER['PATH_TRANSLATED']);
   	}
   	else 
			$this->document_root = str_replace(pb_getenv("SCRIPT_NAME"), '', $_SERVER['PATH_TRANSLATED']);
   	#echo $this->document_root.'<Br />';
   	#echo $_SERVER["SCRIPT_NAME"].'<Br />';
   	#echo $_SERVER["PATH_TRANSLATED"].'<Br />';
  }
  
  /**
   * Converts a string to an array
   * @since Version 2.2.0
   * @access private
   */
  function str_split($string) {
    for ($i=0; $i<strlen($string); $i++) $array[] = $string{$i};
    return $array;
  }
  
  
  /**
   * Convert string into language specified
   * @since Version 2.0.0
   * @access private
   */
  function specialLang($string, $lang) {
    // parse Directory special text style
    switch($lang) {
      case 'elmer': $string = str_replace('l','w',$string);
                    $string = str_replace('r','w',$string);
                    break;
      case 'hacker': $string = strtoupper($string);
                     $string = str_replace('A','&#52;',$string);
                     $string = str_replace('C','&#40;',$string);
                     $string = str_replace('D','&#68;',$string);
                     $string = str_replace('E','&#51;',$string);
                     $string = str_replace('F','&#112;&#104;',$string);
                     $string = str_replace('G','&#54;',$string);
                     $string = str_replace('H','&#125;&#123;',$string);
                     $string = str_replace('I','&#49;',$string);
                     $string = str_replace('M','&#124;&#86;&#124;',$string);
                     $string = str_replace('N','&#124;&#92;&#124;',$string);
                     $string = str_replace('O','&#48;',$string);
                     $string = str_replace('R','&#82;',$string);
                     $string = str_replace('S','&#53;',$string);
                     $string = str_replace('T','&#55;',$string);
                     break;
      case 'pig': $vowels = array('a','A','e','E','i','I','o','O','u','U');
                  $string = $this->str_split($string);
                  $firstLetter = array_shift($string);
                  $string = @implode('',$string);
                  $string = (in_array($firstLetter, $vowels))
                    ? $firstLetter.$string.'yay'
                    : $string.$firstLetter.'ay';
                  break;
      case 'reverse': $string = strrev($string);
                      break;
    }
    return $string;
  }
  
  
  /**
   * Convert string into specified format
   * @since Version 2.2.0
   * @access private
   */
  function dirFormat($string, $format) {
    // parse Directory text style
      switch($format) {
        case 'titlecase': $string = $this->titleCase($string); break;
        case 'ucfirst': $string = ucfirst($string); break;
        case 'ucwords': $string = $this->convertUnderScores($string);
                        $string = ucwords($string); break;
        case 'uppercase': $string = strtoupper($string); break;
        case 'lowercase': $string = strtolower($string); break;
        default: $string = $string;
      }
    return $string;
  }
  
  
  /**
   * TitleCase
   * Convert string into Title Case which excludes capitalizing certain small
   * words.  As in a movie title, or book title. "The Wind in the Trees"
   * @author Justin@gha.bravepages.com, un-thesis@wakeup-people.com, mgm@starlingtech.com, rick@baskettcase.com
   * @access private
   * @since Version 2.3.0
   */
  function titleCase($text) {
    $text = $this->convertUnderScores($text);
    $min_word_len = 4;
    $always_cap_first = TRUE;
    $exclude = array('of','a','the ','and','an','or','nor','but','is','if',
                     'then','else','when','up','at','from','by','on','off',
                     'for','in','out','over','to','into','with','htm','html',
                     'php','phtml'); 

    // Allows for the specification of the minimum length  
    // of characters each word must be in order to be capitalized 

    // Make sure words following punctuation are capitalized 
    $text = str_replace(array('(', '-', '.', '?', ',',':','[',';','!'), 
                        array('( ', '- ', '. ', '? ', ', ',': ','[ ','; ','! '),
                        $text); 

    $words = explode (' ', strtolower($text)); 
    $count = count($words); 
    $num = 0; 
    
    while ($num < $count) { 
      if (strlen($words[$num]) >= $min_word_len 
          && array_search($words[$num], $exclude) === false) 
        $words[$num] = ucfirst($words[$num]); 
      $num++; 
    } 
    
    $text = @implode(' ', $words); 
    $text = str_replace( 
    array('( ', '- ', '. ', '? ', ', ',': ','[ ','; ','! '), 
    array('(', '-', '.', '?', ',',':','[',';','!'), $text); 
    
     // Always capitalize first char if cap_first is true 
    if ($always_cap_first) { 
       if (ctype_alpha($text[0]) && ord($text[0]) <= ord('z') 
          && ord($text[0]) > ord('Z')) 
         $text[0] = chr(ord($text[0]) - 32); 
    }
  
   return $text; 
  }


  
  /**
   * Remove Directories
   * Remove the directories from the breadcrumb
   * @since Version 2.3.2
   * @access private
   */
  function removeDirectories() {
    $numDirs = count($this->scriptArray);
    for ($i=0; $i<$numDirs; $i++) {
      if (!in_array($this->scriptArray[$i], $this->removeDirs))
        $newArray[] = $this->scriptArray[$i];
    }
    return $newArray;
  }


  
  /**
   * Remove File Extension
   * Remove the file extension from the filename
   * @since Version 2.4
   * @access private
   */
  function removeFileExt($filename) {
    $newFileName = @explode('.',$filename);
    return $newFileName[0];
  }


  
  /**
   * Convert Underscores
   * Replace underscores with spaces
   * @since Version 2.4
   * @access private
   */
  function convertUnderScores($name) {
    $varName = str_replace('_',' ',$name);
    return $varName;
  }



  /**
   * Show Breadcrumb
   *
   * Outputs the html formatted breadcrumb according to the variables you set.
   * <code>echo "<p>".$breadcrumb->show_breadcrumb()."</p>";</code>
   * @since Version 0.0.1
   */
  function show_breadcrumb() {
  	$dir = $showLink = $class = $target = '';
  
   // Either set the home element or pop the first empty array off the beginning
    if ($this->homepage != '') $this->scriptArray[0] = $this->homepage;
    else $tmp = array_shift($this->scriptArray);
    
    // if this is a personal site remove the root directory and set
    // new homepage to user directory
    if ($this->personalSite!='') {
    	$this->removeDirs[] = $this->scriptArray[0];
    	if ($this->homepage != '') $this->scriptArray[1] = $this->homepage;
    	else $tmp = array_shift($this->scriptArray);
		}
		    	
    if ($this->homepage=='') $dir = '/';
    
    // Build Path Structure
    $numDirs = count($this->scriptArray);
    
    // BEGIN DIRECTORY FOR LOOP
    for ($i=0; $i<$numDirs; $i++) {
    	$dirTxtName = '';
      #echo $this->changeName[$this->scriptArray[$i]];
      #$dirName = $this->scriptArray[$i];
      $dirName = ($this->changeName[$this->scriptArray[$i]]!='') ? 
                  $this->changeName[$this->scriptArray[$i]] :
                  $this->scriptArray[$i];
                  
      // append the current directory
      if ($this->personalSite!='' && $i==1)
      	$this->scriptArray[$i] = $this->personalSite;
      $dir .= ($i==0 && $this->homepage!='') ? '/' : $this->scriptArray[$i]."/";
      
      // Replace underscores with spaces if _toSpace is set
			if ($this->_toSpace==TRUE) 
				$dirTxtName = $this->convertUnderScores($dirName);
			
			// parse Directory special text style
			$useDirName = ($dirTxtName=='') ? $dirName : $dirTxtName;
			$dirTxtName = $this->specialLang($useDirName, $this->special);

			// Convert string into specified format
			$dirTxtName = $this->dirFormat($dirTxtName, $this->dirformat);

			// Use text instead of images
      if ($this->imagedir['path']=='') $dirName = $dirTxtName;
      // Use Images instead of text
      else {
      	// Set defaults
      	if (!$this->imagedir['type']) $this->imagedir['type'] = 'gif';
      	if (!$this->imagedir['border']) $this->imagedir['border'] = '0';
      	if (!$this->imagedir['id']) $this->imagedir['id'] = TRUE;
      	if (!$this->imagedir['name']) $this->imagedir['name'] = TRUE;
      	if (!$this->imagedir['alt']) $this->imagedir['alt'] = TRUE;
      	
        $dirName = '<img src="' . $this->imagedir['path'] .
        				  $this->scriptArray[$i] . '.' . $this->imagedir['type'] .
        					'" border="' . $this->imagedir['border'] . '"';
        					
				if ($this->imagedir['id']==TRUE) // id
        	$dirName .= ' id="' . $this->scriptArray[$i] . '"';
				if ($this->imagedir['name']==TRUE) // name
        	$dirName .= ' name="' . $this->scriptArray[$i] . '"';
				if ($this->imagedir['alt']==TRUE) { // alt
					$alt = ($this->changeName[$this->scriptArray[$i]]!='') 
									? $this->changeName[$this->scriptArray[$i]]
									:	$this->scriptArray[$i];
					$dirName .= ' alt="' . $alt . '"';
				}
				if ($this->imagedir['title']==TRUE) { // title
					$title = ($this->changeName[$this->scriptArray[$i]]!='') 
									? $this->changeName[$this->scriptArray[$i]]
									:	$this->scriptArray[$i];
					$dirName .= ' title="' . $title . '"';
				}
        					
				if ($this->imagedir['hspace']) // hspace
					$dirName .= ' hspace="' . $this->imagedir['hspace'] . '"';
				if ($this->imagedir['vspace']) // vspace
					$dirName .= ' vspace="' . $this->imagedir['vspace'] . '"';
				if ($this->imagedir['align']) // align
					$dirName .= ' align="' . $this->imagedir['align'] . '"';
				if ($this->imagedir['height']) // height
					$dirName .= ' height="' . $this->imagedir['height'] . '"';
				if ($this->imagedir['width']) // width
					$dirName .= ' width="' . $this->imagedir['width'] . '"';
        
        $dirName .= ' />';
      }
      
      // Add CSS
      if ($this->cssClass!='') $class = ' class="'.$this->cssClass.'"';
      
      // Add frame target
      if ($this->target!='') $target = ' target="'.$this->target.'"';
      
      // create link
      // If fileExists has values then check to make sure one of those files
      // exists, if it does, link it, otherwise do not link
      if ($this->fileExists) {
        for ($k=0; $k<count($this->fileExists); $k++) {
        	if ($this->personalSite!='') {
        		if (strpos($dir, $this->personalSite))
        			$exists_filename = str_replace($this->personalSite.'/', '', $this->document_root.$dir.$this->fileExists[$k]);
						else continue;
					}
					else
						$exists_filename = $this->document_root.$dir.$this->fileExists[$k];
					#echo $exists_filename.'<br />';
          if (file_exists($exists_filename)) {
            $showLink = 'yes';
            break;
          } else $showLink = 'no';
        }
      }
      
      // Set the root filename if it is different then the default index page
      $rootFileName = ($this->rootIndexLink!='' && $dir == '/')
      	? $this->rootIndexLink : '';
      
      if (!in_array($this->scriptArray[$i], $this->removeDirs)) {
      	if ($this->unlinkCurrentDir==TRUE && ($i+1)==$numDirs || $showLink=='no')
         	$breadcrumb[] = $dirName;
      	// If we are not supposed to remove the directory, show it
      	elseif (!in_array($this->scriptArray[$i], $this->removeDirs) || $showLink=='yes') 
					$breadcrumb[] = '<a href="' . $dir . $rootFileName . '"' . $class . $target . ' title="' . $dirTxtName . '">' . $dirName . '</a>';
				elseif ($this->personalSite!='' && $i==1)
					$breadcrumb[] = '<a href="' . $dir . $rootFileName . '"' . $class . $target . ' title="' . $dirTxtName . '">' . $dirName . '</a>';
			}
    }
    // END DIRECTORY FOR LOOP
    
    $fileName = $originalFileName = $this->fileName;
    
    #if ($this->fileNametoTitle==TRUE) $fileName = $this->getPageTitle(); 
    
    // Check to see if hideFileExt is on, if so turn on showfile
    // and remove the file extension
    if ($this->hideFileExt==TRUE) $this->showfile = TRUE;
    
    if ($this->showfile==TRUE) {
      // Change the filename if filename is in changeFileName array
      if ($this->changeFileName[$_SERVER['PHP_SELF']]!='') 
        $fileName = $this->changeFileName[$_SERVER['PHP_SELF']];
      // If it is not then just use $fileName or remove extension if specified
      elseif ($this->hideFileExt==TRUE)
        $fileName = $this->removeFileExt($fileName);
        
      // Convert underscores to spaces
      if ($this->_toSpace==TRUE) 
        $fileName = $this->convertUnderScores($fileName);
      // parse filename special text style
      $fileName = $this->specialLang($fileName, $this->special);
      // Convert string into specified format
      $fileName = $this->dirFormat($fileName, $this->dirformat);
      // Add CSS
      if ($this->cssClass!='') $fileName = '<span class="'.$this->cssClass.'">'.
                                           $fileName.'</span>';
      // Add link to filename
      if ($this->linkFile==TRUE)
        $fileName = '<a href="'.$originalFileName.'">'.$fileName.'</a>';
      $fileName = $this->symbol.$fileName;
    } else $fileName = '';
    
    // Web Server Path
    // return if we are not at root
    if ($numDirs>0) return @implode($this->symbol,$breadcrumb).$fileName;
    // if at root just return the filename
    else return $fileName;
  }
}
?>