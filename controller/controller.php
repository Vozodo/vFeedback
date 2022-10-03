<?php

class Controller
{

    private $context = array();

    public function run($aktion)
    {
        $this->$aktion(); // LOGIK
        $this->generatePage($aktion); //VIEW
    }

    public function home()
    {
        $this->addContext("feedbacks", Feedback::findAll());
    }

    public function create()
    {
        $success = false;
        $err = "";


        if ($_POST) {

            if (HC_ENABLE === true) {
                $data = array(
                    'secret' => HC_SECRETKEY,
                    'response' => $_POST['h-captcha-response']
                );
                $verify = curl_init();
                curl_setopt($verify, CURLOPT_URL, "https://hcaptcha.com/siteverify");
                curl_setopt($verify, CURLOPT_POST, true);
                curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
                curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($verify);
                // var_dump($response);
                $responseData = json_decode($response);
            }

            if ((!empty($_POST['title'])) && (!empty($_POST['feedback'])) && (!empty($_POST['name'])) && (!empty($_POST['accept']))) {
                $feedback = new Feedback();
                $feedback->setTitle($_POST['title']);
                $feedback->setFeedback($_POST['feedback']);
                $feedback->setName($_POST['name']);
                                
                $ip = new Ip();
                $ip->setIpaddress(Functions::get_client_ip());

                if ((Functions::RegEx($feedback) === true) || (C_WORDS_VALIDATION === false)) {
                    if (($responseData->success) || (HC_ENABLE === false)) {
                        if ((Ip::allreadyFilled(Functions::get_client_ip()) === false) || (C_BLOCK_MULTIPLE_INSERTS === false)) {
                            if ((Functions::isBadIP(Functions::get_client_ip()) === false) || (C_VPN_CHECK === false)) {
                                
                                $ip->setDate((new DateTime('now'))->format("Y-m-d H:i:s"));
                                $feedback->setDate((new DateTime('now'))->format("Y-m-d H:i:s"));
                                $feedback->setCountry(Functions::getCountry());

                                $ip->speichere();
                                $feedback->speichere();

                                Functions::sendEmailNotification($feedback, $ip, 1);


                                $success = true;
                            } else {
                                $err = "VPN/Proxys are not allowed, Buddy!";
                                $success = false;
                                Functions::sendEmailNotification($feedback, $ip, 2, $err);
                            }
                        } else {
                            $err = "You have already submitted a feedback in the LAST days!";
                            $success = false;
                            Functions::sendEmailNotification($feedback, $ip, 2, $err);
                        }
                    } else {
                        $err = "Sorry, there was a error with the captcha";
                        $success = false;
                        Functions::sendEmailNotification($feedback, $ip, 2, $err);
                    }
                } else {
                    $err = "Your feedback contains not allowed characters";
                    $success = false;
                    Functions::sendEmailNotification($feedback, $ip, 2, $err);
                }
            } else {
                $err = "You forgot a field";
                $success = false;
            }
        }
        $this->addContext("error", $err);
        $this->addContext("success", $success);
    }

    private function admin()
    {
        if (isset($_SESSION['admin'])) {
            $this->addContext("logedin", true);
        } else {
            header('Location: index.php');
            $this->addContext("logedin", false);
        }
        $this->addContext("feedbacks", Feedback::findAll());
    }

    private function edit()
    {

        $success = false;
        $err = "";


        if (isset($_SESSION['admin'])) {
            if (isset($_GET['id'])) {

                $feedback = Feedback::find($_GET['id']);
                $this->addContext("feedback", $feedback);

                if ($_POST) {
                    if ((!empty($_POST['title'])) && (!empty($_POST['feedback'])) && (!empty($_POST['name']))) {

                        $feedback->setTitle($_POST['title']);
                        $feedback->setFeedback($_POST['feedback']);
                        $feedback->setName($_POST['name']);

                        if ((Functions::RegEx($feedback) === true) || (C_WORDS_VALIDATION === false)) {

                            $feedback->speichere();
                            $success = true;
                        } else {
                            $err = "Your feedback contains not allowed characters";
                            $success = false;
                        }
                    } else {
                        $err = "You forgot a field";
                        $success = false;
                    }
                } 
            }
        } else {
            header("Location: index.php");
        }

        $this->addContext("error", $err);
        $this->addContext("success", $success);
    }

    private function delete()
    {
        
        $success = false;

        if (isset($_SESSION['admin'])) {
            if (isset($_GET['id'])) {

                $feedback = Feedback::find($_GET['id']);
                $this->addContext("feedback", $feedback);

                if ($_POST) {
                    if (!empty($_POST['id'])) {
                        $feedback = Feedback::find($_POST['id']);
                        $feedback->loeschen();
                        $success = true;
                    }
                }

            }
        } else {
            header("Location: index.php");
        }

        $this->addContext("success", $success);

    }



    private function account()
    {
        $err = "";
        if (isset($_SESSION['admin'])) {
            if ($_SESSION['admin'] === true) {
                $this->addContext("logedin", true);
                session_destroy();
            } else {
                $this->addContext("logedin", false);
            }
        } else {
            if ($_POST) {
                if ((!empty($_POST['username'])) && (!empty($_POST['password']))) {
                    if (Functions::AdminLogin($_POST['username'], $_POST['password']) === true) {
                        $this->addContext("logedin", true);
                        header("Location: index.php?a=admin");
                    } else {
                        $err = "Username/Password wrong";
                    }
                }
            } elseif ((isset($_SESSION['admin'])) && ($_SESSION['admin'] === true)) {
                $_POST = array();
                $logedin = true;
            } else {
                $logedin = false;
            }
            $this->addContext("logedin", false);
            $this->addContext("error", $err);
        }
    }

    private function addContext($key, $value)
    {
        $this->context[$key] = $value;
    }

    private function generatePage($template)
    {
        extract($this->context);
        require_once 'view/snippets/header.html';
        require_once 'view/' . $template . ".v.html";
        require_once 'view/snippets/footer.html';
    }
}
