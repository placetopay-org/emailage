<?php

namespace PlacetoPay\Emailage\Messages;

class FlagResponse extends Message
{
    public function flagReason()
    {
        return isset($this->query['fraudcodeID']) ? $this->query['fraudcodeID'] : null;
    }
}
