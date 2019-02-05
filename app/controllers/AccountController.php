<?php

class AccountController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

//    public function actionLogin(string $returnUrl)
//    {
//        !isset($_POST['login']) ? $this->loginGet($returnUrl) : $this->loginPost();
//    }

    public function actionLoginGet(string $returnUrl)
    {
        $this->partialView($returnUrl);
    }

    public function actionLoginPost(string $login, string $password)
    {
        try
        {
            $repository = new AutoStoreDb();
            $loginResult = $repository->login($login, $password, new HashSHA1());
            if ($loginResult->result == 0)
            {
                Session::getSession()['user'] = $loginResult->user;
            }
            unset($loginResult->user);
            echo json_encode($loginResult);
        }
        finally
        {
            $repository->close();
        }
    }

    public function actionAuthorization(string $returnUrl)
    {
        $this->redirect($returnUrl);
    }

    public function actionLogout(string $returnUrl)
    {
        try
        {
            $repository = new AutoStoreDb();
            $repository->logout(Session::getSession()['user']->id);
            Session::unset();
        }
        finally
        {
            $repository->close();
        }
        $this->redirect($returnUrl);
    }

//    public function actionRegistration()
//    {
//        !isset($_POST['login']) ? $this->registrationGet() : $this->registrationPost();
//    }

    public function actionRegistrationGet()
    {
        $this->partialView();
    }

    public function actionRegistrationPost(string $login, string $password, int $roleId = 3)
    {
        try
        {
            $repository = new AutoStoreDb();
            echo json_encode($repository->registration($login, $password, $roleId));
        }
        finally
        {
            $repository->close();
        }
    }

    public function actionCheckLogin()
    {
        if (!array_key_exists('login', $_POST)) return;
        try
        {
            $repository = new AutoStoreDb();
            echo json_encode($repository->isExistsLogin($_POST['login']));
        }
        finally
        {
            $repository->close();
        }

    }
}