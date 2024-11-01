<?php

namespace systasisgfifscrm;

class SystasisGFIFSLogger implements \Infusionsoft_Logger
{
    /*
     * $data array contains the following:
     *  'time' (Y-m-d H:i:s)
     *  'duration' (int - duration of request in seconds)
     *  'method' (string - API call sent)
     *  'args' (array)
     *  'attempts' (int)
     *  'result' (string - Failed or Successful)
     *  'error_message' (string - NULL unless result is Failed)
     */
    public function log(array $data)
    {
        GravityFormsInfusionsoftIntegrator::get_instance()->log_error(print_r($data, true));
    }
}
