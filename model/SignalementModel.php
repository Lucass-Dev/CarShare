<?php

class SignalementModel
{
    private string $file;

    public function __construct()
    {
        $this->file = __DIR__ . '/../../storage_signalements.json';

        if (!file_exists($this->file)) {
            file_put_contents($this->file, json_encode([]));
        }
    }

    public function save(array $signalement): void
    {
        $data = json_decode(file_get_contents($this->file), true);
        if (!is_array($data)) {
            $data = [];
        }

        $data[] = $signalement;

        file_put_contents(
            $this->file,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }
}
