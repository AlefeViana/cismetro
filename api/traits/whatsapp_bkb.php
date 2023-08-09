<?php

/**
 * Caio 2020-07-08
 * This class provides all necessary methods to send messages through whatsapp interface 
 * We are using MessengerPeople endpoints to communicate with facebook 
 */

trait Whatsapp{
    /**
     * Selects whatsapp template     * 
     */
    
    public function templates($index)
    {
        //removing digit "9" from patient cellphone number
        $cellphone_phone = $this->paciente_phone_number;
        $formatted_cellphone_number = (substr($cellphone_phone,0,2).substr($cellphone_phone,3,strlen($cellphone_phone)));
 
        $templates = [
            "appointment_created" => 
            [
                "identifier" =>  "19eb12e7-f3eb-41fe-abed-3e7544bf0651:55".$formatted_cellphone_number,
                "payload" => [
                    "type" => "template",
                    "template" => [
                        "type" => "notification",
                        "notification" => [
                            "name" => "appointment_created",
                            "language" => "pt_BR",
                            "components" => [
                                [
                                    "type" => "body",
                                    "parameters"=>  [
                                        [
                                            "type" => "text",
                                            "text" => $this->procedimento
                                        ],
                                        [
                                            "type" => "text",
                                            "text" => $this->agendamento_data
                                        ],
                                        [
                                            "type" => "text",
                                            "text" => $this->fornecedor_nome
                                        ],
                                        [
                                            "type" => "text",
                                            "text" => $this->fornecedor_endereco
                                        ]
                                    ]
                                ],
                                [
                                    "type" => "button",
                                    "sub_type" => "url",
                                    "index" => "0", 
                                    "parameters" => [
                                        [
                                            "type"=> "text",
                                            "text" => $this->generateAccessToken()
                                        ]
                                    ]
                                ]                                   
                            ]
                        ]
                    ]
                ]
            ],
            "appointments_cancelled" => 
            [
                "identifier" =>  "19eb12e7-f3eb-41fe-abed-3e7544bf0651:55".$formatted_cellphone_number,
                "payload" => [
                    "type" => "template",
                    "template" => [
                        "type" => "notification",
                        "notification" => [
                            "name" => "appointments_cancelled",
                            "language" => "pt_BR",
                            "components" => [
                                [
                                    "type" => "body",
                                    "parameters"=>  [
                                        [
                                            "type" => "text",
                                            "text" => $this->procedimento
                                        ],
                                        [
                                            "type" => "text",
                                            "text" => $this->agendamento_data
                                        ],
                                        [
                                            "type" => "text",
                                            "text" => $this->fornecedor_nome
                                        ],
                                        [
                                            "type" => "text",
                                            "text" => $this->fornecedor_endereco
                                        ]
                                    ]
                                ],
                            ]
                        ]
                    ]
                ]
            ],
            "telemedicine_about_to_begin" => 
            [
                "identifier"=> "19eb12e7-f3eb-41fe-abed-3e7544bf0651:55".$formatted_cellphone_number,
                "payload" => [
                    "type" => "template",
                    "template" =>  [
                        "type" => "notification",
                        "notification" => [
                            "name" => "telemedicine_about_to_begin",
                            "language" => "pt_BR"    
                        ]
                    ]
                ]
            ],
            "telemedicine_initializing" => 
            [
                "identifier"=> "19eb12e7-f3eb-41fe-abed-3e7544bf0651:55".$formatted_cellphone_number,
                "payload" => [
                    "type" => "template",
                    "template" =>  [
                        "type" => "notification",
                        "notification" => [
                            "name" => "telemedicine_initializing",
                            "language" => "pt_BR",
                            "components" => [
                                [
                                    "type" => "button",
                                    "sub_type" => "url",
                                    "index" => "0", 
                                    "parameters" => [
                                        [
                                            "type"=> "text",
                                            "text" => $this->generatePatientToken()
                                        ]
                                    ]
                                ]
                            ] 
                        ]
                    ]
                ]
            ],
            "appointments_reminder_2v" => 
            [
                "identifier"=> "19eb12e7-f3eb-41fe-abed-3e7544bf0651:55".$formatted_cellphone_number,
                "payload" => [
                    "type" => "template",
                    "template" => [
                        "type" => "notification",
                        "notification" => [
                            "name" => "appointments_reminder_2v",
                            "language" => "pt_BR",
                            "components" => [
                                [
                                    "type" => "body",
                                    "parameters"=>  [
                                        [
                                            "type" => "text",
                                            "text" => $this->procedimento
                                        ],
                                        [
                                            "type" => "text",
                                            "text" => $this->agendamento_data
                                        ],
                                        [
                                            "type" => "text",
                                            "text" => $this->fornecedor_nome
                                        ],
                                        [
                                            "type" => "text",
                                            "text" => $this->fornecedor_endereco
                                        ]
                                    ]
                                ],
                                [
                                    "type" => "button",
                                    "sub_type" => "url",
                                    "index" => "0", 
                                    "parameters" => [
                                        [
                                            "type"=> "text",
                                            "text" => $this->generateAccessToken()
                                        ]
                                    ]
                                ]                                   
                            ]
                        ]
                    ]
                ]
            ],
            "appointments_created_v1" => 
            [
                "identifier"=> "19eb12e7-f3eb-41fe-abed-3e7544bf0651:55".$formatted_cellphone_number,
                "payload" => [
                    "type" => "template",
                    "template" => [
                        "type" => "notification",
                        "notification" => [
                            "name" => "appointments_created_v1",
                            "language" => "pt_BR",
                            "components" => [
                                [
                                    "type" => "body",
                                    "parameters"=>  [
                                        [
                                            "type" => "text",
                                            "text" => $this->procedimento
                                        ],
                                        [
                                            "type" => "text",
                                            "text" => $this->agendamento_data
                                        ],
                                        [
                                            "type" => "text",
                                            "text" => $this->fornecedor_nome
                                        ],
                                        [
                                            "type" => "text",
                                            "text" => $this->fornecedor_endereco
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            "appointments_satisfaction_survey" => 
            [
                "identifier"=> "19eb12e7-f3eb-41fe-abed-3e7544bf0651:55".$formatted_cellphone_number,
                "payload" => [
                    "type" => "template",
                    "template" => [
                        "type" => "notification",
                        "notification" => [
                            "name" => "appointments_satisfaction_survey",
                            "language" => "pt_BR",
                            "components" => [
                                [
                                    "type" => "button",
                                    "sub_type" => "url",
                                    "index" => "0", 
                                    "parameters" => [
                                        [
                                            "type"=> "text",
                                            "text" => $this->generateAccessToken()
                                        ]
                                    ]
                                ]                                   
                            ]
                        ]
                    ]
                ]
            ],
        ];

        return $templates[$index];
    }

    /**
     * Whatsapp notifications
     */

    public function notify($template){
        
        // This requires the curl extension to be installed
       $ch = curl_init("https://api.messengerpeople.dev/messages");
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_POST, true);
       curl_setopt($ch, CURLOPT_HTTPHEADER, [
           "Content-Type: application/vnd.messengerpeople.v1+json",
           "Accept: application/vnd.messengerpeople.v1+json",
           "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjRkYjVmYTUwYjZkMjZjN2VhYzQ3OGI3MWIzNTUxNzM4OTBkMzNjMTQwOTQ1NDg4ZDY4MzFmOWFjM2FiMDNjYjdiYTY4OWM2MWNmOWFkNTA3In0.eyJhdWQiOiIwMDVhY2M1YzY2ZTcwZmY4NmUxNTUwOWIiLCJqdGkiOiI0ZGI1ZmE1MGI2ZDI2YzdlYWM0NzhiNzFiMzU1MTczODkwZDMzYzE0MDk0NTQ4OGQ2ODMxZjlhYzNhYjAzY2I3YmE2ODljNjFjZjlhZDUwNyIsImlhdCI6MTU5MzUyMTQ5MiwibmJmIjoxNTkzNTIxNDkyLCJleHAiOjE1OTYxMTM0OTIsInN1YiI6IiIsInNjb3BlcyI6WyJtZXNzYWdlczpzZW5kIl0sImN1c3RvbWVyX2lkIjoxMDA5Mjc3fQ.ElKQbpIqmG3gTm6785TionsYFGYNhw3Njyxqv_VRfmjypcWQj-RjZ0mNV8wCxk8ellMmYTm3eboMhnuV_zCoK9KFnq3I62mX0WFEWp4ZGLEEuRn6U7_ckKng0ME2B0K80oq7bSAa6tdxOEY_wD4qAr7LGK44nbosFCA-rMSpRdBWNFDHo4eVlFU0qzFvAvlK4CoxLyL2Xm9ynRbc0vO9xp86ao6e17n71OTE-yirw56IHQmjhhIBAFlELBswF_p--4VzaTRvkXTEBElHZAMdpGVbrTilUqDMguKNUIT7nbiPiaGNHOWwsZxpBMpdRhbADl_SJB86daZJ-9zXf_4g1Q",
       ]);
       curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->templates($template)) ) ;
       $response = curl_exec($ch);
       curl_close($ch);

       return $response;
    }

    /**
     * Generate the patient token for accessing telemedicine module as patient
     * 
     */

    function generatePatientToken()
    {
        $string = $this->connection_name."%".$this->id."%".$this->paciente_id."%paciente";
        return $this->encrypt_string($string);        
    }

    /**
     * Generate the doctor token for accessing telemedicine module as doctor
     * @params int $doctor_id
     */

    public function generateDoctorToken($doctor_id)
    {
        $string = $this->connection_name."%".$this->id."%".$doctor_id."%medico";
        return $this->encrypt_string($string);        
    }

   
    public function encrypt_string($string){
        return openssl_encrypt($string, 'AES-128-CTR', 'SitconInc', 0, '3138224656319710');
    }
    
    public function decrypt_string($encrypted_string){
        return openssl_decrypt ($encrypted_string, 'AES-128-CTR', 'SitconInc', 0, '3138224656319710');
    }

    /**
     * Generates token for accessing confirmation, medical records, satisfaction survey pages, etc...
     */

    public function generateAccessToken()
    {
        $string = $this->connection_id."%".$this->id."%".$this->paciente_id;
        return $this->encrypt_string($string);        
    }
}