<?php

namespace Controller;

class MainController
{
    use Singleton;

    private static Response $response;

    public function mainPage()
    {
        self::$response = Response::getInstance();
        $data = ['cat' => 'Cat', 'dog' => 'Dog', 'bunny' => 'Bunny', 'duck' => 'Duck', 'elefant' => 'Elefant'];
        return self::$response->view('../view/app.php', $data);

        // return $response->json($data, 200);
        // return $response->redirect('/link');
        // return $response->render('../view/app.php');
    }

    public function getPostResearch()
    {
        echo "<h2><pre>Get All Postgraduate Research</pre></h2>";
    }

    public function getResearchUnder()
    {
        echo "<h2><pre>Get All Undergraduate Research</pre></h2>";
    }

    public function getEvent(int $id, string $cat)
    {
        echo "<pre>id: $id, cat: $cat </pre>";
    }

    public function getEventTest(string $cat, int $id)
    {
        echo "<pre>id: $id, cat: $cat</pre>";
    }

    public function testOnlyOneParameter(int $id)
    {
        echo "<pre>IDN: $id</pre>";
    }

    public function link()
    {
        self::$response = Response::getInstance();
        return self::$response->view('../view/link.php');
    }

    public function newPath()
    {
        self::$response = Response::getInstance();
        echo "<h1>Ya sé PHP</h1>";
    }

    public function latex()
    {
        self::$response = Response::getInstance();
        return self::$response->view('../view/latex.php');
    }

    public function pdf()
    {
        self::$response = Response::getInstance();
        $latex = self::$response->render(
            '../view/templates/latex/sample.php',
            ['data'  => 'Hello World \LaTeX\\']
        );
        file_put_contents('sample.tex', $latex);
        return "Archivo creado...";
    }

    public function execute()
    {
        // latexmk                  = Rutina de generación automática de documentos LaTeX.
        // -pdflua                  = Genera el pdf por LuaLaTeX.
        // -interaction=batchmode   = Previene toda salida pertinente a la compilacion, mostrando solo lo necesario desde una terminal.
        // -interaction=nonstopmode = No solicita entrada después de errores graves, sino que se detiene por completo.
        // -use-make                = Usa el programa make para intentar crear archivos faltantes.
        // -output-directory        = Establece la ruta del directorio para los archivos de salida.

        self::$response = Response::getInstance();

        // Licencia Pública GNU
        //$process = shell_exec("lualatex -interaction=nonstopmode -output-directory=" . $_SERVER['DOCUMENT_ROOT'] . "/latex/ "  . $_SERVER['DOCUMENT_ROOT'] . "/sample.tex");

        // Licencia Pública GNU
        //$process = shell_exec("latexmk -pdflua -interaction=batchmode -use-make -output-directory=" . $_SERVER['DOCUMENT_ROOT'] . "/latex/ "  . $_SERVER['DOCUMENT_ROOT'] . "/sample.tex");

        // Licencia Pública GNU Reducida
        $process = shell_exec("latexmk -pdflatex='xelatex -synctex=1 -interaction=batchmode %O %S' -pdf -use-make -output-directory=" . $_SERVER['DOCUMENT_ROOT'] . "/latex/ "  . $_SERVER['DOCUMENT_ROOT'] . "/sample.tex");

        return self::$response->json([
            'process' => $process
        ], 200);
    }
}
