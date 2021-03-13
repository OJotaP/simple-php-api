<?php

namespace Api\User;

class UserModel
 {

    private static $pdo;

    /**
    * Model Constructor
    */

    public function __construct() {
        self::$pdo = \Api\Database\Database::connection();
    }

    /**
    * Token Verify
    */

    function auth( $token ) {
        $tokens = Array(
            '123',
            '567'
        );

        if ( in_array( $token, $tokens ) ) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
    * Checks if a user already exists
    */

    function checkUser( User $user ) {
        try {
            $sql = 'SELECT id FROM users 
                    WHERE users.username=:username AND users.password=:password';

            $stmt = self::$pdo->prepare( $sql );
            $stmt->bindValue( ':username', $user->getUsername(), \PDO::PARAM_STR );
            $stmt->bindValue( ':password', $user->getPassword(), \PDO::PARAM_STR );
            $stmt->execute();

            if ( $stmt->rowCount() == 1 ) {
                # return ( bool ) TRUE;
                return $stmt->fetch( \PDO::FETCH_ASSOC );
            } else {
                return ( int ) 0;
            }
        } catch ( \PDOException $ex ) {
            throw $ex;
        }
    }

    /**
    * Search user by idKey
    */

    function search( User $user ) {
        try {
            $sql = 'SELECT users.id, users.name, users.email 
                    FROM users WHERE users.id = :id';

            $stmt = self::$pdo->prepare( $sql );
            $stmt->bindValue( ':id', $user->getId(), \PDO::PARAM_INT );
            $stmt->execute();

            if ( $stmt->rowCount() == 1 ) {
                return $stmt->fetch( \PDO::FETCH_ASSOC );
            } else {
                return ( bool ) FALSE;
            }
        } catch ( \PDOException $ex ) {
            throw $ex;
        }
    }

    /**
    * Insert a new User
    */

    function insert( User $user ) {
        try {
            self::$pdo->beginTransaction();

            $sql = 'INSERT INTO users (users.name, users.email, users.username, users.password) 
                    VALUES (:name, :email, :username, :password)';

            $stmt = self::$pdo->prepare( $sql );
            $stmt->bindValue( ':name', $user->getName(), \PDO::PARAM_STR );
            $stmt->bindValue( ':email', $user->getEmail(), \PDO::PARAM_STR );
            $stmt->bindValue( ':username', $user->getUsername(), \PDO::PARAM_STR );
            $stmt->bindValue( ':password', $user->getPassword(), \PDO::PARAM_STR );
            $stmt->execute();

            self::$pdo->commit();

            return ( string ) TRUE;

        } catch( \PDOException $ex ) {
            self::$pdo->rollback();
            throw $ex;
        }
    }

    /**
    * Update User data
    */

    function update( User $user ) {
        try {
            self::$pdo->beginTransaction();

            $sql = 'UPDATE users SET users.name=:name, users.email = :email 
                    WHERE users.id = :id';

            $stmt = self::$pdo->prepare( $sql );
            $stmt->bindValue( ':name', $user->getName(), \PDO::PARAM_STR );
            $stmt->bindValue( ':email', $user->getEmail(), \PDO::PARAM_STR );
            $stmt->bindValue( ':id', $user->getId(), \PDO::PARAM_INT );
            $stmt->execute();

            self::$pdo->commit();

            return ( string ) TRUE;

        } catch( \PDOException $ex ) {
            self::$pdo->rollback();
            throw $ex;
        }
    }
}
