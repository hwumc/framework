<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class Session
{
    public static function start()
    {
        session_start();
    }

    public static function appendError($errorcode)
    {
        if(! isset( $_SESSION['errorqueue'] ) ) {
            $_SESSION['errorqueue'] = array();
        }

        $_SESSION['errorqueue'][] = array('message' => $errorcode, 'type' => 'error');
    }
    
    public static function appendInfo($errorcode)
    {
        if(! isset( $_SESSION['errorqueue'] ) ) {
            $_SESSION['errorqueue'] = array();
        }

        $_SESSION['errorqueue'][] = array('message' => $errorcode, 'type' => 'info');
    }
    
    public static function appendWarning($errorcode)
    {
        if(! isset( $_SESSION['errorqueue'] ) ) {
            $_SESSION['errorqueue'] = array();
        }

        $_SESSION['errorqueue'][] = array('message' => $errorcode, 'type' => 'warning');
    }
    
    public static function appendSuccess($errorcode)
    {
        if(! isset( $_SESSION['errorqueue'] ) ) {
            $_SESSION['errorqueue'] = array();
        }

        $_SESSION['errorqueue'][] = array('message' => $errorcode, 'type' => 'success');
    }

    public static function getErrorQueue()
    {
        return isset($_SESSION['errorqueue']) ? $_SESSION['errorqueue'] : array();
    }

    public static function clearErrorQueue()
    {
        $_SESSION['errorqueue'] = array();
    }

    public static function setLoggedInUser($id)
    {
        $_SESSION['uid'] = $id;
    }

    public static function addSessionRight( $right ) {
        if( (! isset( $_SESSION['session_rights'] )) || (! is_array( $_SESSION['session_rights'] )) ) {
            $_SESSION['session_rights'] = array();
        }

        $_SESSION['session_rights'][] = $right;
    }

    public static function getSessionRights() {
        return isset($_SESSION['session_rights']) ? $_SESSION['session_rights'] : array();
    }

    public static function getLoggedInUser()
    {
        if(isset($_SESSION['uid']))
        {
            return $_SESSION['uid'];
        }
        else
        {
            return false;
        }
    }

    public static function isLoggedIn()
    {
        return isset($_SESSION['uid']);
    }

    public static function destroy()
    {
        session_destroy();
    }
}