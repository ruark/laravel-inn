<?php

namespace Ruark\LaravelInn;

use Ruark\LaravelInn\Exceptions\InnValidationException;

class InnValidator
{
    private ?string $belonging = null;

    /**
     * @throws InnValidationException
     */
    public function validate(?string $value, array $parameters): bool
    {
        // l - legal, i - individual, null - all
        $spec = $parameters[0] ?? null;
        if ($spec && !in_array($spec, ['i', 'l'])) {
            throw new InnValidationException(('Invalid inn validation specification "' . $spec . '"'));
        }
        if (!$this->validateCommonInn($value)) {
            return false;
        }

        if ($this->belonging === 'legal' && ($spec === 'l' || !$spec)) {
            return $this->validateLegalInn($value);
        }

        if ($this->belonging === 'individual' && ($spec === 'i' || !$spec)) {
            return $this->validateIndividualInn($value);
        }

        return false;
    }

    /**
     * Сообщение при ошибке валидации
     * @return string
     */
    public static function getMessageBag(): string
    {
        $lang = __('validation.inn');
        if (!$lang || $lang == 'validation.inn') {
            return config('inn.default_validation_message');
        }
        return $lang;
    }

    /**
     * Валидация общих признаков
     * @param string|null $value
     * @return bool
     */
    protected function validateCommonInn(?string $value): bool
    {
        if (!$value || !is_numeric($value)) {
            return false;
        }
        if (!$this->defineBelonging($value)) {
            return false;
        }
        return true;
    }

    /**
     * Проверка контрольной суммы для ИНН физического лица
     * @param string $value
     * @return bool
     * @throws InnValidationException
     */
    protected function validateIndividualInn(string $value): bool
    {
        if (strlen($value) !== 12) {
            return false;
        }

        $weight_1 = $this->calcInnWeight($value, [7, 2, 4, 10, 3, 5, 9, 4, 6, 8, 0]);
        $weight_1 = $weight_1 % 11;
        if ($weight_1 > 9) {
            $weight_1 = $weight_1 % 10;
        }

        $weight_2 = $this->calcInnWeight($value, [3, 7, 2, 4, 10, 3, 5, 9, 4, 6, 8, 0]);
        $weight_2 = $weight_2 % 11;
        if ($weight_2 > 9) {
            $weight_2 = $weight_2 % 10;
        }

        if ($weight_1 != $value[10] || $weight_2 != $value[11]) {
            return false;
        }

        return true;
    }

    /**
     * Проверка контрольной суммы для ИНН юридического лица
     * @param string $value
     * @return bool
     * @throws InnValidationException
     */
    protected function validateLegalInn(string $value): bool
    {
        if (strlen($value) !== 10) {
            return false;
        }

        $weight = $this->calcInnWeight($value, [2, 4, 10, 3, 5, 9, 4, 6, 8, 0]);
        $weight = $weight % 11;
        if ($weight > 9) {
            $weight = $weight % 10;
        }

        if ($weight != $value[9]) {
            return false;
        }
        return true;
    }

    /**
     * Расчет веса ИНН с учетом весового коэффициента
     * @param string $value
     * @param array $rule
     * @return int
     * @throws InnValidationException
     */
    public function calcInnWeight(string $value, array $rule): int
    {
        if (count($rule) > strlen($value)) {
            throw new InnValidationException('Invalid rule length for this value');
        }

        $result = 0;
        foreach ($rule as $index => $w) {
            $result += (int)$value[$index] * $w;
        }
        return $result;
    }

    /**
     * @param string $value
     * @return bool
     */
    protected function defineBelonging(string $value): bool
    {
        $rules = config('inn.len_rules ');
        $len = strlen($value);
        if (array_key_exists($len, $rules)) {
            $this->belonging = $rules[$len];
            return true;
        }
        return false;
    }
}
