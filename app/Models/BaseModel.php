<?php

namespace App\Models;

use App\Services\LocaleService;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

trait BaseModel
{

    public function __construct(array $attributes = [])
    {
        if (method_exists($this, "localeFields")) {
            foreach (LocaleService::getLocaleFields($this->localeFields()) as $field) {
                $this->fillable[] = $field;
            }
        }
        parent::__construct($attributes);
    }

    /**
     * @throws Exception
     */
    public static function findOrField($id, $message = "", $error = null, $code = 404)
    {
        $error = $error ?? __("not:found");

        $class = parent::find($id);

        $message = $message != "" ? $message . "|" . $error : $error;
        if (!$class) {
            throw new Exception($message);
        }

        return $class;
    }

    function getAttribute($key)
    {
        $lang = App::currentLocale() ?? "uz";
        if (!in_array($lang, Config::get("app.locales"))) $lang = "uz";

        if (method_exists($this, "localeFields") and in_array($key, $this->localeFields())) {
            $data = parent::getAttribute($key . "_" . $lang);
            if (method_exists($this, "hashFields") and in_array($key . "_" . $lang, $this->hashFields())) {
                $data = base64_decode($data);
            }
            return $data;
        }
        $data = parent::getAttribute($key);
        if (method_exists($this, "hashFields") and in_array($key, $this->hashFields())) {
            $data = base64_decode($data);
        }
        return $data;
    }


}
