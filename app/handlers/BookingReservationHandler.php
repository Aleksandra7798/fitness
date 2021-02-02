<?php

class BookingReservationHandler extends BookingReservationDAO
{
    private $reservation;
    private $pricing;
    private $executionFeedback;

    public function __construct(Reservation $reservation, Pricing $pricing)
    {
        $this->reservation = $reservation;
        $this->pricing = $pricing;
    }

    public function getExecutionFeedback()
    {
        return $this->executionFeedback;
    }

    public function setExecutionFeedback($executionFeedback)
    {
        $this->executionFeedback = $executionFeedback;
    }

    public function getReservation()
    {
        return $this->reservation;
    }

    public function setReservation($reservation)
    {
        $this->reservation = $reservation;
    }

    public function create()
    {
        $dao = new BookingReservationDAO();
        if ($dao->insert($this->reservation, $this->pricing)) {
            $this->setExecutionFeedback(
                array(
                "heading" => "Super, zarezerwowałeś/aś trening w klubie AleksFitness!",
                "content" => "Cieszymy się, że rozpoczniesz z nami ćwiczenia.",
                "footer"  => "Czekaj na potwierdzenie rezerwacji. Status rezerwacji możesz sprawdzić w profilu klienta. <br/> Do zobaczenia!"
                )
            );
        } else {
            $this->setExecutionFeedback("Błąd serwera! Spróbuj ponownie później.");
        }
    }
}
