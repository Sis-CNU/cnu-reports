<?php

namespace Controller;

/**
 * Clase MainController
 */
class MainController
{
    // Uso de patrón de diseño Singleton.
    use Singleton;

    /**
     * Variable Response
     *
     * @var Response
     */
    private static Response $response;
    private static Session $session;

    private function __construct()
    {
        self::$response = Response::getInstance();
        self::$session = Session::getInstance();

    }

    public function mainPage()
    {

        $http_protocol = $_SERVER['SERVER_PROTOCOL'];
        $started = self::$session->start("lexmark");
        //self::$session->close();

        $var = self::$session->check();
        $id = self::$session->getId();
        //$var = true;

        $data = ['id'=>$id, 'started'=>$var, 'cat' => 'Cat', 'dog' => 'Dog', 'bunny' => 'Bunny', 'duck' => 'Duck', 'elefant' => 'Elefant', 'http_protocol' => $http_protocol];
        //$data = ['cat' => 'Cat', 'dog' => 'Dog', 'bunny' => 'Bunny', 'duck' => 'Duck', 'elefant' => 'Elefant', 'http_protocol' => $http_protocol];
        return self::$response->view('../view/app.php',$data);

        // return self::$response->view('../view/app.php', $data);
        // return self::$response->json($data, 200);
        // return self::$response->redirect('/redirect');
        // return self::$response->render('../view/app.php');

        // echo phpinfo();        

        echo Database::execute();
        echo Database::fetch();
        echo Database::test();
    }

    public function redirect()
    {
        echo "<h2><pre>We Here Into Redirected Page</pre></h2>";
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
