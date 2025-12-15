<?php
class HomeModel {

    private string $strHelloWorld = "Hello World from Model";

    public function getStrHelloWorld(): string {
        return $this->strHelloWorld;
    }
}


?>