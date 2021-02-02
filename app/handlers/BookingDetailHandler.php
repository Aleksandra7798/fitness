<?php

class BookingDetailHandler extends BookingDetailDAO
{
    public function __construct()
    {
    }

    private $executionFeedback;

    public function getExecutionFeedback()
    {
        return $this->executionFeedback;
    }

    public function setExecutionFeedback($executionFeedback)
    {
        $this->executionFeedback = $executionFeedback;
    }

    public function getAllBookings()
    {
        if ($this->fetchBooking()) {
            return $this->fetchBooking();
        } else {
            return Util::DB_SERVER_ERROR;
        }
    }

    public function getCustomerBookings(Customer $c)
    {
        if ($this->fetchBookingByCid($c->getId())) {
            $this->setExecutionFeedback(1);
            return $this->fetchBookingByCid($c->getId());
        }
        return $this->setExecutionFeedback(0);
    }


    public function getOczekujaca()
    {
        $count = 0;
        $oczekujaca = \models\StatusEnum::OCZEKUJACA_STR;
        foreach ($this->getAllBookings() as $v) {
            if (($v["status"] == $oczekujaca) || (strtoupper($v["status"]) == $oczekujaca)) {
                $count++;
            }
        }
        return $count;
    }

    public function getPotwierdzona()
    {
        $count = 0;
        $potwierdzona = \models\StatusEnum::POTWIERDZONA_STR;
        foreach ($this->getAllBookings() as $v) {
            if (($v["status"] == $potwierdzona) || (strtoupper($v["status"]) == $potwierdzona)) {
                $count++;
            }
        }
        return $count;
    }

    public function getAnulowana()
    {
        $count = 0;
        $anulowana = \models\StatusEnum::ANULOWANA_STR;
        foreach ($this->getAllBookings() as $v) {
            if (($v["status"] == $anulowana) || (strtoupper($v["status"]) == $anulowana)) {
                $count++;
            }
        }
        return $count;
    }

    

    public function confirmSelection($item)
    {
        for ($i = 0; $i < count($item); $i++) {
            if ($this->updatePotwierdzona($item[$i])) {
                $out = "Rezerwacje zostały <b> POTWIERDZONE </b>. <br/>";
                $out .= "Poczekaj chwile. Strona zostanie ponownie załadowana!";
                $this->setExecutionFeedback($out);
            } else {
                $this->setExecutionFeedback("Spróbuj ponownie później. Musiał wystąpić błąd podczas przetwarzania Twojego żądania.");
            }
        }
    }

    public function cancelSelection($item)
    {
        for ($i = 0; $i < count($item); $i++) {
         
            if ($this->updateAnulowana($item[$i])) {
                $out = "Rezerwacje zostały pomyślnie <b> ANULOWANE </b>. <br/>";
                $out .= "Poczekaj chwile. Strona zostanie ponownie załadowana!";
                $this->setExecutionFeedback($out);
            } else {
                $this->setExecutionFeedback("Spróbuj ponownie później. Musiał wystąpić błąd podczas przetwarzania Twojego żądania.");
            }
        }
    }
}
