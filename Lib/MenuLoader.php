<?php

	namespace Sil\Core;

    use RecursiveDirectoryIterator;
    use RecursiveTreeIterator;
    use Exception;

    /**
     * Carrega o menu auxiliar da página
     * @author Luiz de Souza
     */


	class MenuLoader2{

				private function getFiles($path):RecursiveDirectoryIterator
				{
					$listaDir = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS);
					return $listaDir;
				}

        /**
        *   Carrega e adiciona links ao Menu a partir do caminho gerado em 'pathConstruct'
        */

        public function loadMenu($path, $class = '')
        {
						if(is_dir($path))
						{

            	$listaDir = $this->getFiles($path);

            	foreach ($listaDir as $file) {
								if(is_dir($file) && $file->getFilename() <> 'images' && $file->getFilename() <> 'docs')
            			{
										$files = $this->getFiles($file);
											foreach ($files as $subfile) {
												if($subfile->getExtension() == 'php')
												{
													$class = trim($subfile->getFilename(), '.php');
												}
											}
										$path = $file->getPath();
										$resource = $file->getFilename();
										$this->links[] = "<a href='index.php?path=$path/$resource&class=$class'>$resource</a>";
              		}
							}
            }

            if (empty($this->links))
            	{
              	return '';
              }

            else
            	{
              	return implode('</br>', $this->links);
              }
         }


        /**
        * Carrega e adiciona links à barra de navegação a partir de pathConstruct
        */

        public function navLoad($path)
        {
						$dirload = 'App' . DIRECTORY_SEPARATOR . 'Control' . DIRECTORY_SEPARATOR;
						$links = array(); //path variável, que incrementa a cada iteração
						$navlinks = array();
            $dir = array_slice(explode(DIRECTORY_SEPARATOR, $path), 2); // Retira o prefixo '$dirload' para que
						//$dir = array_slice($dir, 2);								// não apareça na barra de navegação

            foreach ($dir as $resource)
						{
								array_push($links, $resource);

								$files = $this->getFiles($dirload . implode(DIRECTORY_SEPARATOR, $links));

								foreach ($files as $file) {
									if($file->getExtension() == 'php' && $dir[count($dir) -1] == $resource)
									{
										array_push($navlinks, "<h3 style='display: inline-block;'>$resource</h3>");
									}

									else if ($file->getExtension() == 'php') {
										$subpath = $file->getPath();
										$class = trim($file->getBasename(), '.php') . ' ';
										array_push($navlinks, "<a href='index.php?path=$subpath&class=$class'><h3 style='display: inline-block;'>$resource</h3></a>");
									}
								}
						  }

            return implode(' ' . DIRECTORY_SEPARATOR . ' ', $navlinks);
					}
	}
