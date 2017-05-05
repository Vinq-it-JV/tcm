<?php

namespace NotificationBundle\Model\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \DateTime;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelCollection;
use \PropelDateTime;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use DeviceBundle\Model\CbInput;
use DeviceBundle\Model\CbInputQuery;
use DeviceBundle\Model\ControllerBox;
use DeviceBundle\Model\ControllerBoxQuery;
use DeviceBundle\Model\DsTemperatureSensor;
use DeviceBundle\Model\DsTemperatureSensorQuery;
use NotificationBundle\Model\DeviceNotification;
use NotificationBundle\Model\DeviceNotificationPeer;
use NotificationBundle\Model\DeviceNotificationQuery;
use UserBundle\Model\User;
use UserBundle\Model\UserQuery;

abstract class BaseDeviceNotification extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'NotificationBundle\\Model\\DeviceNotificationPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        DeviceNotificationPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinite loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the temperature field.
     * Note: this column has a database default value of: '0'
     * @var        string
     */
    protected $temperature;

    /**
     * The value for the switch_state field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $switch_state;

    /**
     * The value for the is_handled field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $is_handled;

    /**
     * The value for the handled_by field.
     * @var        int
     */
    protected $handled_by;

    /**
     * The value for the created_at field.
     * @var        string
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     * @var        string
     */
    protected $updated_at;

    /**
     * @var        User
     */
    protected $aUser;

    /**
     * @var        PropelObjectCollection|ControllerBox[] Collection to store aggregation of ControllerBox objects.
     */
    protected $collControllerBoxen;
    protected $collControllerBoxenPartial;

    /**
     * @var        PropelObjectCollection|DsTemperatureSensor[] Collection to store aggregation of DsTemperatureSensor objects.
     */
    protected $collDsTemperatureSensors;
    protected $collDsTemperatureSensorsPartial;

    /**
     * @var        PropelObjectCollection|CbInput[] Collection to store aggregation of CbInput objects.
     */
    protected $collCbInputs;
    protected $collCbInputsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInSave = false;

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * Flag to prevent endless clearAllReferences($deep=true) loop, if this object is referenced
     * @var        boolean
     */
    protected $alreadyInClearAllReferencesDeep = false;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $controllerBoxenScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $dsTemperatureSensorsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $cbInputsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->temperature = '0';
        $this->switch_state = false;
        $this->is_handled = false;
    }

    /**
     * Initializes internal state of BaseDeviceNotification object.
     * @see        applyDefaults()
     */
    public function __construct()
    {
        parent::__construct();
        $this->applyDefaultValues();
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {

        return $this->id;
    }

    /**
     * Get the [temperature] column value.
     *
     * @return string
     */
    public function getTemperature()
    {

        return $this->temperature;
    }

    /**
     * Get the [switch_state] column value.
     *
     * @return boolean
     */
    public function getSwitchState()
    {

        return $this->switch_state;
    }

    /**
     * Get the [is_handled] column value.
     *
     * @return boolean
     */
    public function getIsHandled()
    {

        return $this->is_handled;
    }

    /**
     * Get the [handled_by] column value.
     *
     * @return int
     */
    public function getHandledBy()
    {

        return $this->handled_by;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = null)
    {
        if ($this->created_at === null) {
            return null;
        }

        if ($this->created_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->created_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->created_at, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = null)
    {
        if ($this->updated_at === null) {
            return null;
        }

        if ($this->updated_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->updated_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->updated_at, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return DeviceNotification The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = DeviceNotificationPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [temperature] column.
     *
     * @param  string $v new value
     * @return DeviceNotification The current object (for fluent API support)
     */
    public function setTemperature($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->temperature !== $v) {
            $this->temperature = $v;
            $this->modifiedColumns[] = DeviceNotificationPeer::TEMPERATURE;
        }


        return $this;
    } // setTemperature()

    /**
     * Sets the value of the [switch_state] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return DeviceNotification The current object (for fluent API support)
     */
    public function setSwitchState($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->switch_state !== $v) {
            $this->switch_state = $v;
            $this->modifiedColumns[] = DeviceNotificationPeer::SWITCH_STATE;
        }


        return $this;
    } // setSwitchState()

    /**
     * Sets the value of the [is_handled] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return DeviceNotification The current object (for fluent API support)
     */
    public function setIsHandled($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_handled !== $v) {
            $this->is_handled = $v;
            $this->modifiedColumns[] = DeviceNotificationPeer::IS_HANDLED;
        }


        return $this;
    } // setIsHandled()

    /**
     * Set the value of [handled_by] column.
     *
     * @param  int $v new value
     * @return DeviceNotification The current object (for fluent API support)
     */
    public function setHandledBy($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->handled_by !== $v) {
            $this->handled_by = $v;
            $this->modifiedColumns[] = DeviceNotificationPeer::HANDLED_BY;
        }

        if ($this->aUser !== null && $this->aUser->getId() !== $v) {
            $this->aUser = null;
        }


        return $this;
    } // setHandledBy()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return DeviceNotification The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = DeviceNotificationPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return DeviceNotification The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = DeviceNotificationPeer::UPDATED_AT;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
            if ($this->temperature !== '0') {
                return false;
            }

            if ($this->switch_state !== false) {
                return false;
            }

            if ($this->is_handled !== false) {
                return false;
            }

        // otherwise, everything was equal, so return true
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
     * @param int $startcol 0-based offset column which indicates which resultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false)
    {
        try {

            $this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->temperature = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->switch_state = ($row[$startcol + 2] !== null) ? (boolean) $row[$startcol + 2] : null;
            $this->is_handled = ($row[$startcol + 3] !== null) ? (boolean) $row[$startcol + 3] : null;
            $this->handled_by = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->created_at = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->updated_at = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 7; // 7 = DeviceNotificationPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating DeviceNotification object", $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {

        if ($this->aUser !== null && $this->handled_by !== $this->aUser->getId()) {
            $this->aUser = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param boolean $deep (optional) Whether to also de-associated any related objects.
     * @param PropelPDO $con (optional) The PropelPDO connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getConnection(DeviceNotificationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = DeviceNotificationPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aUser = null;
            $this->collControllerBoxen = null;

            $this->collDsTemperatureSensors = null;

            $this->collCbInputs = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param PropelPDO $con
     * @return void
     * @throws PropelException
     * @throws Exception
     * @see        BaseObject::setDeleted()
     * @see        BaseObject::isDeleted()
     */
    public function delete(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(DeviceNotificationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = DeviceNotificationQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @throws Exception
     * @see        doSave()
     */
    public function save(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(DeviceNotificationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(DeviceNotificationPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(DeviceNotificationPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(DeviceNotificationPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                DeviceNotificationPeer::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see        save()
     */
    protected function doSave(PropelPDO $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aUser !== null) {
                if ($this->aUser->isModified() || $this->aUser->isNew()) {
                    $affectedRows += $this->aUser->save($con);
                }
                $this->setUser($this->aUser);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->controllerBoxenScheduledForDeletion !== null) {
                if (!$this->controllerBoxenScheduledForDeletion->isEmpty()) {
                    foreach ($this->controllerBoxenScheduledForDeletion as $controllerBox) {
                        // need to save related object because we set the relation to null
                        $controllerBox->save($con);
                    }
                    $this->controllerBoxenScheduledForDeletion = null;
                }
            }

            if ($this->collControllerBoxen !== null) {
                foreach ($this->collControllerBoxen as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->dsTemperatureSensorsScheduledForDeletion !== null) {
                if (!$this->dsTemperatureSensorsScheduledForDeletion->isEmpty()) {
                    foreach ($this->dsTemperatureSensorsScheduledForDeletion as $dsTemperatureSensor) {
                        // need to save related object because we set the relation to null
                        $dsTemperatureSensor->save($con);
                    }
                    $this->dsTemperatureSensorsScheduledForDeletion = null;
                }
            }

            if ($this->collDsTemperatureSensors !== null) {
                foreach ($this->collDsTemperatureSensors as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->cbInputsScheduledForDeletion !== null) {
                if (!$this->cbInputsScheduledForDeletion->isEmpty()) {
                    foreach ($this->cbInputsScheduledForDeletion as $cbInput) {
                        // need to save related object because we set the relation to null
                        $cbInput->save($con);
                    }
                    $this->cbInputsScheduledForDeletion = null;
                }
            }

            if ($this->collCbInputs !== null) {
                foreach ($this->collCbInputs as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param PropelPDO $con
     *
     * @throws PropelException
     * @see        doSave()
     */
    protected function doInsert(PropelPDO $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[] = DeviceNotificationPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . DeviceNotificationPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(DeviceNotificationPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(DeviceNotificationPeer::TEMPERATURE)) {
            $modifiedColumns[':p' . $index++]  = '`temperature`';
        }
        if ($this->isColumnModified(DeviceNotificationPeer::SWITCH_STATE)) {
            $modifiedColumns[':p' . $index++]  = '`switch_state`';
        }
        if ($this->isColumnModified(DeviceNotificationPeer::IS_HANDLED)) {
            $modifiedColumns[':p' . $index++]  = '`is_handled`';
        }
        if ($this->isColumnModified(DeviceNotificationPeer::HANDLED_BY)) {
            $modifiedColumns[':p' . $index++]  = '`handled_by`';
        }
        if ($this->isColumnModified(DeviceNotificationPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(DeviceNotificationPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `device_notification` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`id`':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '`temperature`':
                        $stmt->bindValue($identifier, $this->temperature, PDO::PARAM_STR);
                        break;
                    case '`switch_state`':
                        $stmt->bindValue($identifier, (int) $this->switch_state, PDO::PARAM_INT);
                        break;
                    case '`is_handled`':
                        $stmt->bindValue($identifier, (int) $this->is_handled, PDO::PARAM_INT);
                        break;
                    case '`handled_by`':
                        $stmt->bindValue($identifier, $this->handled_by, PDO::PARAM_INT);
                        break;
                    case '`created_at`':
                        $stmt->bindValue($identifier, $this->created_at, PDO::PARAM_STR);
                        break;
                    case '`updated_at`':
                        $stmt->bindValue($identifier, $this->updated_at, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param PropelPDO $con
     *
     * @see        doSave()
     */
    protected function doUpdate(PropelPDO $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();
        BasePeer::doUpdate($selectCriteria, $valuesCriteria, $con);
    }

    /**
     * Array of ValidationFailed objects.
     * @var        array ValidationFailed[]
     */
    protected $validationFailures = array();

    /**
     * Gets any ValidationFailed objects that resulted from last call to validate().
     *
     *
     * @return array ValidationFailed[]
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
    }

    /**
     * Validates the objects modified field values and all objects related to this table.
     *
     * If $columns is either a column name or an array of column names
     * only those columns are validated.
     *
     * @param mixed $columns Column name or an array of column names.
     * @return boolean Whether all columns pass validation.
     * @see        doValidate()
     * @see        getValidationFailures()
     */
    public function validate($columns = null)
    {
        $res = $this->doValidate($columns);
        if ($res === true) {
            $this->validationFailures = array();

            return true;
        }

        $this->validationFailures = $res;

        return false;
    }

    /**
     * This function performs the validation work for complex object models.
     *
     * In addition to checking the current object, all related objects will
     * also be validated.  If all pass then <code>true</code> is returned; otherwise
     * an aggregated array of ValidationFailed objects will be returned.
     *
     * @param array $columns Array of column names to validate.
     * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objects otherwise.
     */
    protected function doValidate($columns = null)
    {
        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            $failureMap = array();


            // We call the validate method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aUser !== null) {
                if (!$this->aUser->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aUser->getValidationFailures());
                }
            }


            if (($retval = DeviceNotificationPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collControllerBoxen !== null) {
                    foreach ($this->collControllerBoxen as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collDsTemperatureSensors !== null) {
                    foreach ($this->collDsTemperatureSensors as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collCbInputs !== null) {
                    foreach ($this->collCbInputs as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }


            $this->alreadyInValidation = false;
        }

        return (!empty($failureMap) ? $failureMap : true);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *               one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *               BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *               Defaults to BasePeer::TYPE_PHPNAME
     * @return mixed Value of field.
     */
    public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = DeviceNotificationPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getTemperature();
                break;
            case 2:
                return $this->getSwitchState();
                break;
            case 3:
                return $this->getIsHandled();
                break;
            case 4:
                return $this->getHandledBy();
                break;
            case 5:
                return $this->getCreatedAt();
                break;
            case 6:
                return $this->getUpdatedAt();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     *                    BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                    Defaults to BasePeer::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to true.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['DeviceNotification'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['DeviceNotification'][$this->getPrimaryKey()] = true;
        $keys = DeviceNotificationPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getTemperature(),
            $keys[2] => $this->getSwitchState(),
            $keys[3] => $this->getIsHandled(),
            $keys[4] => $this->getHandledBy(),
            $keys[5] => $this->getCreatedAt(),
            $keys[6] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aUser) {
                $result['User'] = $this->aUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collControllerBoxen) {
                $result['ControllerBoxen'] = $this->collControllerBoxen->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collDsTemperatureSensors) {
                $result['DsTemperatureSensors'] = $this->collDsTemperatureSensors->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCbInputs) {
                $result['CbInputs'] = $this->collCbInputs->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name peer name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                     Defaults to BasePeer::TYPE_PHPNAME
     * @return void
     */
    public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = DeviceNotificationPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

        $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setTemperature($value);
                break;
            case 2:
                $this->setSwitchState($value);
                break;
            case 3:
                $this->setIsHandled($value);
                break;
            case 4:
                $this->setHandledBy($value);
                break;
            case 5:
                $this->setCreatedAt($value);
                break;
            case 6:
                $this->setUpdatedAt($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     * The default key type is the column's BasePeer::TYPE_PHPNAME
     *
     * @param array  $arr     An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
    {
        $keys = DeviceNotificationPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setTemperature($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setSwitchState($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setIsHandled($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setHandledBy($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setCreatedAt($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setUpdatedAt($arr[$keys[6]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(DeviceNotificationPeer::DATABASE_NAME);

        if ($this->isColumnModified(DeviceNotificationPeer::ID)) $criteria->add(DeviceNotificationPeer::ID, $this->id);
        if ($this->isColumnModified(DeviceNotificationPeer::TEMPERATURE)) $criteria->add(DeviceNotificationPeer::TEMPERATURE, $this->temperature);
        if ($this->isColumnModified(DeviceNotificationPeer::SWITCH_STATE)) $criteria->add(DeviceNotificationPeer::SWITCH_STATE, $this->switch_state);
        if ($this->isColumnModified(DeviceNotificationPeer::IS_HANDLED)) $criteria->add(DeviceNotificationPeer::IS_HANDLED, $this->is_handled);
        if ($this->isColumnModified(DeviceNotificationPeer::HANDLED_BY)) $criteria->add(DeviceNotificationPeer::HANDLED_BY, $this->handled_by);
        if ($this->isColumnModified(DeviceNotificationPeer::CREATED_AT)) $criteria->add(DeviceNotificationPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(DeviceNotificationPeer::UPDATED_AT)) $criteria->add(DeviceNotificationPeer::UPDATED_AT, $this->updated_at);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(DeviceNotificationPeer::DATABASE_NAME);
        $criteria->add(DeviceNotificationPeer::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param  int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of DeviceNotification (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setTemperature($this->getTemperature());
        $copyObj->setSwitchState($this->getSwitchState());
        $copyObj->setIsHandled($this->getIsHandled());
        $copyObj->setHandledBy($this->getHandledBy());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getControllerBoxen() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addControllerBox($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getDsTemperatureSensors() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addDsTemperatureSensor($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCbInputs() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCbInput($relObj->copy($deepCopy));
                }
            }

            //unflag object copy
            $this->startCopy = false;
        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return DeviceNotification Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Returns a peer instance associated with this om.
     *
     * Since Peer classes are not to have any instance attributes, this method returns the
     * same instance for all member of this class. The method could therefore
     * be static, but this would prevent one from overriding the behavior.
     *
     * @return DeviceNotificationPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new DeviceNotificationPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a User object.
     *
     * @param                  User $v
     * @return DeviceNotification The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUser(User $v = null)
    {
        if ($v === null) {
            $this->setHandledBy(NULL);
        } else {
            $this->setHandledBy($v->getId());
        }

        $this->aUser = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the User object, it will not be re-added.
        if ($v !== null) {
            $v->addDeviceNotification($this);
        }


        return $this;
    }


    /**
     * Get the associated User object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return User The associated User object.
     * @throws PropelException
     */
    public function getUser(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aUser === null && ($this->handled_by !== null) && $doQuery) {
            $this->aUser = UserQuery::create()->findPk($this->handled_by, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUser->addDeviceNotifications($this);
             */
        }

        return $this->aUser;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('ControllerBox' == $relationName) {
            $this->initControllerBoxen();
        }
        if ('DsTemperatureSensor' == $relationName) {
            $this->initDsTemperatureSensors();
        }
        if ('CbInput' == $relationName) {
            $this->initCbInputs();
        }
    }

    /**
     * Clears out the collControllerBoxen collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return DeviceNotification The current object (for fluent API support)
     * @see        addControllerBoxen()
     */
    public function clearControllerBoxen()
    {
        $this->collControllerBoxen = null; // important to set this to null since that means it is uninitialized
        $this->collControllerBoxenPartial = null;

        return $this;
    }

    /**
     * reset is the collControllerBoxen collection loaded partially
     *
     * @return void
     */
    public function resetPartialControllerBoxen($v = true)
    {
        $this->collControllerBoxenPartial = $v;
    }

    /**
     * Initializes the collControllerBoxen collection.
     *
     * By default this just sets the collControllerBoxen collection to an empty array (like clearcollControllerBoxen());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initControllerBoxen($overrideExisting = true)
    {
        if (null !== $this->collControllerBoxen && !$overrideExisting) {
            return;
        }
        $this->collControllerBoxen = new PropelObjectCollection();
        $this->collControllerBoxen->setModel('ControllerBox');
    }

    /**
     * Gets an array of ControllerBox objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this DeviceNotification is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|ControllerBox[] List of ControllerBox objects
     * @throws PropelException
     */
    public function getControllerBoxen($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collControllerBoxenPartial && !$this->isNew();
        if (null === $this->collControllerBoxen || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collControllerBoxen) {
                // return empty collection
                $this->initControllerBoxen();
            } else {
                $collControllerBoxen = ControllerBoxQuery::create(null, $criteria)
                    ->filterByDeviceNotification($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collControllerBoxenPartial && count($collControllerBoxen)) {
                      $this->initControllerBoxen(false);

                      foreach ($collControllerBoxen as $obj) {
                        if (false == $this->collControllerBoxen->contains($obj)) {
                          $this->collControllerBoxen->append($obj);
                        }
                      }

                      $this->collControllerBoxenPartial = true;
                    }

                    $collControllerBoxen->getInternalIterator()->rewind();

                    return $collControllerBoxen;
                }

                if ($partial && $this->collControllerBoxen) {
                    foreach ($this->collControllerBoxen as $obj) {
                        if ($obj->isNew()) {
                            $collControllerBoxen[] = $obj;
                        }
                    }
                }

                $this->collControllerBoxen = $collControllerBoxen;
                $this->collControllerBoxenPartial = false;
            }
        }

        return $this->collControllerBoxen;
    }

    /**
     * Sets a collection of ControllerBox objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $controllerBoxen A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return DeviceNotification The current object (for fluent API support)
     */
    public function setControllerBoxen(PropelCollection $controllerBoxen, PropelPDO $con = null)
    {
        $controllerBoxenToDelete = $this->getControllerBoxen(new Criteria(), $con)->diff($controllerBoxen);


        $this->controllerBoxenScheduledForDeletion = $controllerBoxenToDelete;

        foreach ($controllerBoxenToDelete as $controllerBoxRemoved) {
            $controllerBoxRemoved->setDeviceNotification(null);
        }

        $this->collControllerBoxen = null;
        foreach ($controllerBoxen as $controllerBox) {
            $this->addControllerBox($controllerBox);
        }

        $this->collControllerBoxen = $controllerBoxen;
        $this->collControllerBoxenPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ControllerBox objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related ControllerBox objects.
     * @throws PropelException
     */
    public function countControllerBoxen(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collControllerBoxenPartial && !$this->isNew();
        if (null === $this->collControllerBoxen || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collControllerBoxen) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getControllerBoxen());
            }
            $query = ControllerBoxQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDeviceNotification($this)
                ->count($con);
        }

        return count($this->collControllerBoxen);
    }

    /**
     * Method called to associate a ControllerBox object to this object
     * through the ControllerBox foreign key attribute.
     *
     * @param    ControllerBox $l ControllerBox
     * @return DeviceNotification The current object (for fluent API support)
     */
    public function addControllerBox(ControllerBox $l)
    {
        if ($this->collControllerBoxen === null) {
            $this->initControllerBoxen();
            $this->collControllerBoxenPartial = true;
        }

        if (!in_array($l, $this->collControllerBoxen->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddControllerBox($l);

            if ($this->controllerBoxenScheduledForDeletion and $this->controllerBoxenScheduledForDeletion->contains($l)) {
                $this->controllerBoxenScheduledForDeletion->remove($this->controllerBoxenScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	ControllerBox $controllerBox The controllerBox object to add.
     */
    protected function doAddControllerBox($controllerBox)
    {
        $this->collControllerBoxen[]= $controllerBox;
        $controllerBox->setDeviceNotification($this);
    }

    /**
     * @param	ControllerBox $controllerBox The controllerBox object to remove.
     * @return DeviceNotification The current object (for fluent API support)
     */
    public function removeControllerBox($controllerBox)
    {
        if ($this->getControllerBoxen()->contains($controllerBox)) {
            $this->collControllerBoxen->remove($this->collControllerBoxen->search($controllerBox));
            if (null === $this->controllerBoxenScheduledForDeletion) {
                $this->controllerBoxenScheduledForDeletion = clone $this->collControllerBoxen;
                $this->controllerBoxenScheduledForDeletion->clear();
            }
            $this->controllerBoxenScheduledForDeletion[]= $controllerBox;
            $controllerBox->setDeviceNotification(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DeviceNotification is new, it will return
     * an empty collection; or if this DeviceNotification has previously
     * been saved, it will retrieve related ControllerBoxen from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DeviceNotification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|ControllerBox[] List of ControllerBox objects
     */
    public function getControllerBoxenJoinStore($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ControllerBoxQuery::create(null, $criteria);
        $query->joinWith('Store', $join_behavior);

        return $this->getControllerBoxen($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DeviceNotification is new, it will return
     * an empty collection; or if this DeviceNotification has previously
     * been saved, it will retrieve related ControllerBoxen from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DeviceNotification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|ControllerBox[] List of ControllerBox objects
     */
    public function getControllerBoxenJoinDeviceGroup($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ControllerBoxQuery::create(null, $criteria);
        $query->joinWith('DeviceGroup', $join_behavior);

        return $this->getControllerBoxen($query, $con);
    }

    /**
     * Clears out the collDsTemperatureSensors collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return DeviceNotification The current object (for fluent API support)
     * @see        addDsTemperatureSensors()
     */
    public function clearDsTemperatureSensors()
    {
        $this->collDsTemperatureSensors = null; // important to set this to null since that means it is uninitialized
        $this->collDsTemperatureSensorsPartial = null;

        return $this;
    }

    /**
     * reset is the collDsTemperatureSensors collection loaded partially
     *
     * @return void
     */
    public function resetPartialDsTemperatureSensors($v = true)
    {
        $this->collDsTemperatureSensorsPartial = $v;
    }

    /**
     * Initializes the collDsTemperatureSensors collection.
     *
     * By default this just sets the collDsTemperatureSensors collection to an empty array (like clearcollDsTemperatureSensors());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initDsTemperatureSensors($overrideExisting = true)
    {
        if (null !== $this->collDsTemperatureSensors && !$overrideExisting) {
            return;
        }
        $this->collDsTemperatureSensors = new PropelObjectCollection();
        $this->collDsTemperatureSensors->setModel('DsTemperatureSensor');
    }

    /**
     * Gets an array of DsTemperatureSensor objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this DeviceNotification is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|DsTemperatureSensor[] List of DsTemperatureSensor objects
     * @throws PropelException
     */
    public function getDsTemperatureSensors($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collDsTemperatureSensorsPartial && !$this->isNew();
        if (null === $this->collDsTemperatureSensors || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collDsTemperatureSensors) {
                // return empty collection
                $this->initDsTemperatureSensors();
            } else {
                $collDsTemperatureSensors = DsTemperatureSensorQuery::create(null, $criteria)
                    ->filterByDeviceNotification($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collDsTemperatureSensorsPartial && count($collDsTemperatureSensors)) {
                      $this->initDsTemperatureSensors(false);

                      foreach ($collDsTemperatureSensors as $obj) {
                        if (false == $this->collDsTemperatureSensors->contains($obj)) {
                          $this->collDsTemperatureSensors->append($obj);
                        }
                      }

                      $this->collDsTemperatureSensorsPartial = true;
                    }

                    $collDsTemperatureSensors->getInternalIterator()->rewind();

                    return $collDsTemperatureSensors;
                }

                if ($partial && $this->collDsTemperatureSensors) {
                    foreach ($this->collDsTemperatureSensors as $obj) {
                        if ($obj->isNew()) {
                            $collDsTemperatureSensors[] = $obj;
                        }
                    }
                }

                $this->collDsTemperatureSensors = $collDsTemperatureSensors;
                $this->collDsTemperatureSensorsPartial = false;
            }
        }

        return $this->collDsTemperatureSensors;
    }

    /**
     * Sets a collection of DsTemperatureSensor objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $dsTemperatureSensors A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return DeviceNotification The current object (for fluent API support)
     */
    public function setDsTemperatureSensors(PropelCollection $dsTemperatureSensors, PropelPDO $con = null)
    {
        $dsTemperatureSensorsToDelete = $this->getDsTemperatureSensors(new Criteria(), $con)->diff($dsTemperatureSensors);


        $this->dsTemperatureSensorsScheduledForDeletion = $dsTemperatureSensorsToDelete;

        foreach ($dsTemperatureSensorsToDelete as $dsTemperatureSensorRemoved) {
            $dsTemperatureSensorRemoved->setDeviceNotification(null);
        }

        $this->collDsTemperatureSensors = null;
        foreach ($dsTemperatureSensors as $dsTemperatureSensor) {
            $this->addDsTemperatureSensor($dsTemperatureSensor);
        }

        $this->collDsTemperatureSensors = $dsTemperatureSensors;
        $this->collDsTemperatureSensorsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related DsTemperatureSensor objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related DsTemperatureSensor objects.
     * @throws PropelException
     */
    public function countDsTemperatureSensors(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collDsTemperatureSensorsPartial && !$this->isNew();
        if (null === $this->collDsTemperatureSensors || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collDsTemperatureSensors) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getDsTemperatureSensors());
            }
            $query = DsTemperatureSensorQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDeviceNotification($this)
                ->count($con);
        }

        return count($this->collDsTemperatureSensors);
    }

    /**
     * Method called to associate a DsTemperatureSensor object to this object
     * through the DsTemperatureSensor foreign key attribute.
     *
     * @param    DsTemperatureSensor $l DsTemperatureSensor
     * @return DeviceNotification The current object (for fluent API support)
     */
    public function addDsTemperatureSensor(DsTemperatureSensor $l)
    {
        if ($this->collDsTemperatureSensors === null) {
            $this->initDsTemperatureSensors();
            $this->collDsTemperatureSensorsPartial = true;
        }

        if (!in_array($l, $this->collDsTemperatureSensors->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddDsTemperatureSensor($l);

            if ($this->dsTemperatureSensorsScheduledForDeletion and $this->dsTemperatureSensorsScheduledForDeletion->contains($l)) {
                $this->dsTemperatureSensorsScheduledForDeletion->remove($this->dsTemperatureSensorsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	DsTemperatureSensor $dsTemperatureSensor The dsTemperatureSensor object to add.
     */
    protected function doAddDsTemperatureSensor($dsTemperatureSensor)
    {
        $this->collDsTemperatureSensors[]= $dsTemperatureSensor;
        $dsTemperatureSensor->setDeviceNotification($this);
    }

    /**
     * @param	DsTemperatureSensor $dsTemperatureSensor The dsTemperatureSensor object to remove.
     * @return DeviceNotification The current object (for fluent API support)
     */
    public function removeDsTemperatureSensor($dsTemperatureSensor)
    {
        if ($this->getDsTemperatureSensors()->contains($dsTemperatureSensor)) {
            $this->collDsTemperatureSensors->remove($this->collDsTemperatureSensors->search($dsTemperatureSensor));
            if (null === $this->dsTemperatureSensorsScheduledForDeletion) {
                $this->dsTemperatureSensorsScheduledForDeletion = clone $this->collDsTemperatureSensors;
                $this->dsTemperatureSensorsScheduledForDeletion->clear();
            }
            $this->dsTemperatureSensorsScheduledForDeletion[]= $dsTemperatureSensor;
            $dsTemperatureSensor->setDeviceNotification(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DeviceNotification is new, it will return
     * an empty collection; or if this DeviceNotification has previously
     * been saved, it will retrieve related DsTemperatureSensors from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DeviceNotification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|DsTemperatureSensor[] List of DsTemperatureSensor objects
     */
    public function getDsTemperatureSensorsJoinStore($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = DsTemperatureSensorQuery::create(null, $criteria);
        $query->joinWith('Store', $join_behavior);

        return $this->getDsTemperatureSensors($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DeviceNotification is new, it will return
     * an empty collection; or if this DeviceNotification has previously
     * been saved, it will retrieve related DsTemperatureSensors from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DeviceNotification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|DsTemperatureSensor[] List of DsTemperatureSensor objects
     */
    public function getDsTemperatureSensorsJoinDeviceGroup($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = DsTemperatureSensorQuery::create(null, $criteria);
        $query->joinWith('DeviceGroup', $join_behavior);

        return $this->getDsTemperatureSensors($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DeviceNotification is new, it will return
     * an empty collection; or if this DeviceNotification has previously
     * been saved, it will retrieve related DsTemperatureSensors from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DeviceNotification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|DsTemperatureSensor[] List of DsTemperatureSensor objects
     */
    public function getDsTemperatureSensorsJoinControllerBox($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = DsTemperatureSensorQuery::create(null, $criteria);
        $query->joinWith('ControllerBox', $join_behavior);

        return $this->getDsTemperatureSensors($query, $con);
    }

    /**
     * Clears out the collCbInputs collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return DeviceNotification The current object (for fluent API support)
     * @see        addCbInputs()
     */
    public function clearCbInputs()
    {
        $this->collCbInputs = null; // important to set this to null since that means it is uninitialized
        $this->collCbInputsPartial = null;

        return $this;
    }

    /**
     * reset is the collCbInputs collection loaded partially
     *
     * @return void
     */
    public function resetPartialCbInputs($v = true)
    {
        $this->collCbInputsPartial = $v;
    }

    /**
     * Initializes the collCbInputs collection.
     *
     * By default this just sets the collCbInputs collection to an empty array (like clearcollCbInputs());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCbInputs($overrideExisting = true)
    {
        if (null !== $this->collCbInputs && !$overrideExisting) {
            return;
        }
        $this->collCbInputs = new PropelObjectCollection();
        $this->collCbInputs->setModel('CbInput');
    }

    /**
     * Gets an array of CbInput objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this DeviceNotification is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|CbInput[] List of CbInput objects
     * @throws PropelException
     */
    public function getCbInputs($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCbInputsPartial && !$this->isNew();
        if (null === $this->collCbInputs || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCbInputs) {
                // return empty collection
                $this->initCbInputs();
            } else {
                $collCbInputs = CbInputQuery::create(null, $criteria)
                    ->filterByDeviceNotification($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collCbInputsPartial && count($collCbInputs)) {
                      $this->initCbInputs(false);

                      foreach ($collCbInputs as $obj) {
                        if (false == $this->collCbInputs->contains($obj)) {
                          $this->collCbInputs->append($obj);
                        }
                      }

                      $this->collCbInputsPartial = true;
                    }

                    $collCbInputs->getInternalIterator()->rewind();

                    return $collCbInputs;
                }

                if ($partial && $this->collCbInputs) {
                    foreach ($this->collCbInputs as $obj) {
                        if ($obj->isNew()) {
                            $collCbInputs[] = $obj;
                        }
                    }
                }

                $this->collCbInputs = $collCbInputs;
                $this->collCbInputsPartial = false;
            }
        }

        return $this->collCbInputs;
    }

    /**
     * Sets a collection of CbInput objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $cbInputs A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return DeviceNotification The current object (for fluent API support)
     */
    public function setCbInputs(PropelCollection $cbInputs, PropelPDO $con = null)
    {
        $cbInputsToDelete = $this->getCbInputs(new Criteria(), $con)->diff($cbInputs);


        $this->cbInputsScheduledForDeletion = $cbInputsToDelete;

        foreach ($cbInputsToDelete as $cbInputRemoved) {
            $cbInputRemoved->setDeviceNotification(null);
        }

        $this->collCbInputs = null;
        foreach ($cbInputs as $cbInput) {
            $this->addCbInput($cbInput);
        }

        $this->collCbInputs = $cbInputs;
        $this->collCbInputsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CbInput objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related CbInput objects.
     * @throws PropelException
     */
    public function countCbInputs(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collCbInputsPartial && !$this->isNew();
        if (null === $this->collCbInputs || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCbInputs) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCbInputs());
            }
            $query = CbInputQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDeviceNotification($this)
                ->count($con);
        }

        return count($this->collCbInputs);
    }

    /**
     * Method called to associate a CbInput object to this object
     * through the CbInput foreign key attribute.
     *
     * @param    CbInput $l CbInput
     * @return DeviceNotification The current object (for fluent API support)
     */
    public function addCbInput(CbInput $l)
    {
        if ($this->collCbInputs === null) {
            $this->initCbInputs();
            $this->collCbInputsPartial = true;
        }

        if (!in_array($l, $this->collCbInputs->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCbInput($l);

            if ($this->cbInputsScheduledForDeletion and $this->cbInputsScheduledForDeletion->contains($l)) {
                $this->cbInputsScheduledForDeletion->remove($this->cbInputsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	CbInput $cbInput The cbInput object to add.
     */
    protected function doAddCbInput($cbInput)
    {
        $this->collCbInputs[]= $cbInput;
        $cbInput->setDeviceNotification($this);
    }

    /**
     * @param	CbInput $cbInput The cbInput object to remove.
     * @return DeviceNotification The current object (for fluent API support)
     */
    public function removeCbInput($cbInput)
    {
        if ($this->getCbInputs()->contains($cbInput)) {
            $this->collCbInputs->remove($this->collCbInputs->search($cbInput));
            if (null === $this->cbInputsScheduledForDeletion) {
                $this->cbInputsScheduledForDeletion = clone $this->collCbInputs;
                $this->cbInputsScheduledForDeletion->clear();
            }
            $this->cbInputsScheduledForDeletion[]= $cbInput;
            $cbInput->setDeviceNotification(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DeviceNotification is new, it will return
     * an empty collection; or if this DeviceNotification has previously
     * been saved, it will retrieve related CbInputs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DeviceNotification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|CbInput[] List of CbInput objects
     */
    public function getCbInputsJoinStore($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CbInputQuery::create(null, $criteria);
        $query->joinWith('Store', $join_behavior);

        return $this->getCbInputs($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DeviceNotification is new, it will return
     * an empty collection; or if this DeviceNotification has previously
     * been saved, it will retrieve related CbInputs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DeviceNotification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|CbInput[] List of CbInput objects
     */
    public function getCbInputsJoinControllerBox($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CbInputQuery::create(null, $criteria);
        $query->joinWith('ControllerBox', $join_behavior);

        return $this->getCbInputs($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DeviceNotification is new, it will return
     * an empty collection; or if this DeviceNotification has previously
     * been saved, it will retrieve related CbInputs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DeviceNotification.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|CbInput[] List of CbInput objects
     */
    public function getCbInputsJoinDeviceGroup($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CbInputQuery::create(null, $criteria);
        $query->joinWith('DeviceGroup', $join_behavior);

        return $this->getCbInputs($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->temperature = null;
        $this->switch_state = null;
        $this->is_handled = null;
        $this->handled_by = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->alreadyInClearAllReferencesDeep = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volume/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep && !$this->alreadyInClearAllReferencesDeep) {
            $this->alreadyInClearAllReferencesDeep = true;
            if ($this->collControllerBoxen) {
                foreach ($this->collControllerBoxen as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collDsTemperatureSensors) {
                foreach ($this->collDsTemperatureSensors as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCbInputs) {
                foreach ($this->collCbInputs as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aUser instanceof Persistent) {
              $this->aUser->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collControllerBoxen instanceof PropelCollection) {
            $this->collControllerBoxen->clearIterator();
        }
        $this->collControllerBoxen = null;
        if ($this->collDsTemperatureSensors instanceof PropelCollection) {
            $this->collDsTemperatureSensors->clearIterator();
        }
        $this->collDsTemperatureSensors = null;
        if ($this->collCbInputs instanceof PropelCollection) {
            $this->collCbInputs->clearIterator();
        }
        $this->collCbInputs = null;
        $this->aUser = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(DeviceNotificationPeer::DEFAULT_STRING_FORMAT);
    }

    /**
     * return true is the object is in saving state
     *
     * @return boolean
     */
    public function isAlreadyInSave()
    {
        return $this->alreadyInSave;
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     DeviceNotification The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = DeviceNotificationPeer::UPDATED_AT;

        return $this;
    }

}
