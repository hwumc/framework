<?php
// check for invalid entry point
if(!defined("HMS")) die("Invalid entry point");

class ExtensionUnavailableException extends Exception {}

class SmartyTemplateNotFoundException extends SmartyException{}

class ArgumentException extends Exception{}

class SaveFailedException extends Exception{}

class LoginFailedException extends Exception{}

class CreateUserException extends Exception{}

class NonexistantObjectException extends Exception{}
class InvalidChecksumException extends Exception{}

class AccessDeniedException extends Exception{}

class YouShouldntBeDoingThatException extends Exception{}
class WeirdWonderfulException extends Exception{}

class DeprecatedException extends Exception{}

class MissingFieldException extends Exception{}

class NotImplementedException extends Exception{}

class FieldTooLargeException extends Exception{}