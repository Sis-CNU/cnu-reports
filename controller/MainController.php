<?php

namespace Controller;

class MainController
{
    public static function mainPage()
    {
        $response = new Response();
        $data = ['Cat', 'Dog', 'Bunny', 'Duck', 'Elefant'];
        return $response->view('../view/app.php', $data);
    }

    public static function link()
    {
        $response = new Response();
        return $response->view('../view/link.php');
    }

    public static function newPath()
    {
        $response = new Response();
        echo "<h1>Ya sé PHP</h1>";
    }

    public static function latex()
    {
        $response = new Response();
        return $response->view('../view/latex.php');
    }

    public static function pdf()
    {
        $response = new Response();
        $latex = $response->render(
            '../view/templates/latex/sample.php',
            ['data'  => 'Hello World \LaTeX\\']
        );
        file_put_contents('sample.tex', $latex);
        return "Archivo creado...";
    }

    public static function execute()
    {
        // latexmk                  = Rutina de generación automática de documentos LaTeX.
        // -pdflua                  = Genera el pdf por LuaLaTeX.
        // -interaction=batchmode   = Previene toda salida pertinente a la compilacion, mostrando solo lo necesario desde una terminal.
        // -interaction=nonstopmode = No solicita entrada después de errores graves, sino que se detiene por completo.
        // -use-make                = Usa el programa make para intentar crear archivos faltantes.
        // -output-directory        = Establece la ruta del directorio para los archivos de salida.

        $response = new Response();

        // Licencia Pública GNU
        //$process = shell_exec("lualatex -interaction=nonstopmode -output-directory=" . $_SERVER['DOCUMENT_ROOT'] . "/latex/ "  . $_SERVER['DOCUMENT_ROOT'] . "/sample.tex");

        // Licencia Pública GNU
        //$process = shell_exec("latexmk -pdflua -interaction=batchmode -use-make -output-directory=" . $_SERVER['DOCUMENT_ROOT'] . "/latex/ "  . $_SERVER['DOCUMENT_ROOT'] . "/sample.tex");

        // Licencia Pública GNU Reducida
        $process = shell_exec("latexmk -pdflatex='xelatex -synctex=1 -interaction=batchmode %O %S' -pdf -use-make -output-directory=" . $_SERVER['DOCUMENT_ROOT'] . "/latex/ "  . $_SERVER['DOCUMENT_ROOT'] . "/sample.tex");
        
        return $response->json([
            'process' => $process
        ], 200);
    }
}
