<?php

class AdminPage extends AuthenticationPage
{
    protected function prepareData(): void
    {
        parent::prepareData();
        if (!$this->user->admin)
        {
            throw new BadRequestException();
        }


    }
}