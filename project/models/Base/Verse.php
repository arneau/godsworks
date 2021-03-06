<?php

namespace Base;

use \Book as ChildBook;
use \BookQuery as ChildBookQuery;
use \Passage as ChildPassage;
use \PassageQuery as ChildPassageQuery;
use \Tag as ChildTag;
use \TagQuery as ChildTagQuery;
use \Verse as ChildVerse;
use \VerseQuery as ChildVerseQuery;
use \Exception;
use \PDO;
use Map\VerseTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;

/**
 * Base class that represents a row from the 'verse' table.
 *
 *
 *
* @package    propel.generator..Base
*/
abstract class Verse implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Map\\VerseTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the book_id field.
     *
     * @var        int
     */
    protected $book_id;

    /**
     * The value for the chapter_number field.
     *
     * @var        int
     */
    protected $chapter_number;

    /**
     * The value for the verse_number field.
     *
     * @var        int
     */
    protected $verse_number;

    /**
     * The value for the id field.
     *
     * @var        int
     */
    protected $id;

    /**
     * @var        ChildBook
     */
    protected $aBook;

    /**
     * @var        ObjectCollection|ChildPassage[] Collection to store aggregation of ChildPassage objects.
     */
    protected $collPassages;
    protected $collPassagesPartial;

    /**
     * @var        ObjectCollection|ChildTag[] Collection to store aggregation of ChildTag objects.
     */
    protected $collTags;
    protected $collTagsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    // aggregate_column_relation_number_of_chapters_aggregate behavior
    /**
     * @var ChildBook
     */
    protected $oldBookNumberOfChapters;

    // aggregate_column_relation_number_of_verses_aggregate behavior
    /**
     * @var ChildBook
     */
    protected $oldBookNumberOfVerses;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildPassage[]
     */
    protected $passagesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildTag[]
     */
    protected $tagsScheduledForDeletion = null;

    /**
     * Initializes internal state of Base\Verse object.
     */
    public function __construct()
    {
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>Verse</code> instance.  If
     * <code>obj</code> is an instance of <code>Verse</code>, delegates to
     * <code>equals(Verse)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|Verse The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        $cls = new \ReflectionClass($this);
        $propertyNames = [];
        foreach($cls->getProperties() as $property) {
            $propertyNames[] = $property->getName();
        }
        return $propertyNames;
    }

    /**
     * Get the [book_id] column value.
     *
     * @return int
     */
    public function getBookId()
    {
        return $this->book_id;
    }

    /**
     * Get the [chapter_number] column value.
     *
     * @return int
     */
    public function getChapterNumber()
    {
        return $this->chapter_number;
    }

    /**
     * Get the [verse_number] column value.
     *
     * @return int
     */
    public function getVerseNumber()
    {
        return $this->verse_number;
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
     * Set the value of [book_id] column.
     *
     * @param int $v new value
     * @return $this|\Verse The current object (for fluent API support)
     */
    public function setBookId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->book_id !== $v) {
            $this->book_id = $v;
            $this->modifiedColumns[VerseTableMap::COL_BOOK_ID] = true;
        }

        if ($this->aBook !== null && $this->aBook->getId() !== $v) {
            $this->aBook = null;
        }

        return $this;
    } // setBookId()

    /**
     * Set the value of [chapter_number] column.
     *
     * @param int $v new value
     * @return $this|\Verse The current object (for fluent API support)
     */
    public function setChapterNumber($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->chapter_number !== $v) {
            $this->chapter_number = $v;
            $this->modifiedColumns[VerseTableMap::COL_CHAPTER_NUMBER] = true;
        }

        return $this;
    } // setChapterNumber()

    /**
     * Set the value of [verse_number] column.
     *
     * @param int $v new value
     * @return $this|\Verse The current object (for fluent API support)
     */
    public function setVerseNumber($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->verse_number !== $v) {
            $this->verse_number = $v;
            $this->modifiedColumns[VerseTableMap::COL_VERSE_NUMBER] = true;
        }

        return $this;
    } // setVerseNumber()

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\Verse The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[VerseTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

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
        // otherwise, everything was equal, so return TRUE
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
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : VerseTableMap::translateFieldName('BookId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->book_id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : VerseTableMap::translateFieldName('ChapterNumber', TableMap::TYPE_PHPNAME, $indexType)];
            $this->chapter_number = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : VerseTableMap::translateFieldName('VerseNumber', TableMap::TYPE_PHPNAME, $indexType)];
            $this->verse_number = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : VerseTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 4; // 4 = VerseTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Verse'), 0, $e);
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
        if ($this->aBook !== null && $this->book_id !== $this->aBook->getId()) {
            $this->aBook = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(VerseTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildVerseQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aBook = null;
            $this->collPassages = null;

            $this->collTags = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Verse::setDeleted()
     * @see Verse::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(VerseTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildVerseQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(VerseTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $isInsert = $this->isNew();
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                // aggregate_column_relation_number_of_chapters_aggregate behavior
                $this->updateRelatedBookNumberOfChapters($con);
                // aggregate_column_relation_number_of_verses_aggregate behavior
                $this->updateRelatedBookNumberOfVerses($con);
                VerseTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aBook !== null) {
                if ($this->aBook->isModified() || $this->aBook->isNew()) {
                    $affectedRows += $this->aBook->save($con);
                }
                $this->setBook($this->aBook);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            if ($this->passagesScheduledForDeletion !== null) {
                if (!$this->passagesScheduledForDeletion->isEmpty()) {
                    \PassageQuery::create()
                        ->filterByPrimaryKeys($this->passagesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->passagesScheduledForDeletion = null;
                }
            }

            if ($this->collPassages !== null) {
                foreach ($this->collPassages as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->tagsScheduledForDeletion !== null) {
                if (!$this->tagsScheduledForDeletion->isEmpty()) {
                    \TagQuery::create()
                        ->filterByPrimaryKeys($this->tagsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->tagsScheduledForDeletion = null;
                }
            }

            if ($this->collTags !== null) {
                foreach ($this->collTags as $referrerFK) {
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
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[VerseTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . VerseTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(VerseTableMap::COL_BOOK_ID)) {
            $modifiedColumns[':p' . $index++]  = 'book_id';
        }
        if ($this->isColumnModified(VerseTableMap::COL_CHAPTER_NUMBER)) {
            $modifiedColumns[':p' . $index++]  = 'chapter_number';
        }
        if ($this->isColumnModified(VerseTableMap::COL_VERSE_NUMBER)) {
            $modifiedColumns[':p' . $index++]  = 'verse_number';
        }
        if ($this->isColumnModified(VerseTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }

        $sql = sprintf(
            'INSERT INTO verse (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'book_id':
                        $stmt->bindValue($identifier, $this->book_id, PDO::PARAM_INT);
                        break;
                    case 'chapter_number':
                        $stmt->bindValue($identifier, $this->chapter_number, PDO::PARAM_INT);
                        break;
                    case 'verse_number':
                        $stmt->bindValue($identifier, $this->verse_number, PDO::PARAM_INT);
                        break;
                    case 'id':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = VerseTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getBookId();
                break;
            case 1:
                return $this->getChapterNumber();
                break;
            case 2:
                return $this->getVerseNumber();
                break;
            case 3:
                return $this->getId();
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
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['Verse'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Verse'][$this->hashCode()] = true;
        $keys = VerseTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getBookId(),
            $keys[1] => $this->getChapterNumber(),
            $keys[2] => $this->getVerseNumber(),
            $keys[3] => $this->getId(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aBook) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'book';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'book';
                        break;
                    default:
                        $key = 'Book';
                }

                $result[$key] = $this->aBook->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collPassages) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'passages';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'passages';
                        break;
                    default:
                        $key = 'Passages';
                }

                $result[$key] = $this->collPassages->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collTags) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'tags';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'tags';
                        break;
                    default:
                        $key = 'Tags';
                }

                $result[$key] = $this->collTags->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\Verse
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = VerseTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\Verse
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setBookId($value);
                break;
            case 1:
                $this->setChapterNumber($value);
                break;
            case 2:
                $this->setVerseNumber($value);
                break;
            case 3:
                $this->setId($value);
                break;
        } // switch()

        return $this;
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
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = VerseTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setBookId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setChapterNumber($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setVerseNumber($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setId($arr[$keys[3]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\Verse The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(VerseTableMap::DATABASE_NAME);

        if ($this->isColumnModified(VerseTableMap::COL_BOOK_ID)) {
            $criteria->add(VerseTableMap::COL_BOOK_ID, $this->book_id);
        }
        if ($this->isColumnModified(VerseTableMap::COL_CHAPTER_NUMBER)) {
            $criteria->add(VerseTableMap::COL_CHAPTER_NUMBER, $this->chapter_number);
        }
        if ($this->isColumnModified(VerseTableMap::COL_VERSE_NUMBER)) {
            $criteria->add(VerseTableMap::COL_VERSE_NUMBER, $this->verse_number);
        }
        if ($this->isColumnModified(VerseTableMap::COL_ID)) {
            $criteria->add(VerseTableMap::COL_ID, $this->id);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildVerseQuery::create();
        $criteria->add(VerseTableMap::COL_ID, $this->id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
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
     * @param       int $key Primary key.
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
     * @param      object $copyObj An object of \Verse (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setBookId($this->getBookId());
        $copyObj->setChapterNumber($this->getChapterNumber());
        $copyObj->setVerseNumber($this->getVerseNumber());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getPassages() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPassage($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getTags() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addTag($relObj->copy($deepCopy));
                }
            }

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
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \Verse Clone of current object.
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
     * Declares an association between this object and a ChildBook object.
     *
     * @param  ChildBook $v
     * @return $this|\Verse The current object (for fluent API support)
     * @throws PropelException
     */
    public function setBook(ChildBook $v = null)
    {
        // aggregate_column_relation behavior
        if (null !== $this->aBook && $v !== $this->aBook) {
            $this->oldBookNumberOfVerses = $this->aBook;
        }
        // aggregate_column_relation behavior
        if (null !== $this->aBook && $v !== $this->aBook) {
            $this->oldBookNumberOfChapters = $this->aBook;
        }
        if ($v === null) {
            $this->setBookId(NULL);
        } else {
            $this->setBookId($v->getId());
        }

        $this->aBook = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildBook object, it will not be re-added.
        if ($v !== null) {
            $v->addVerse($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildBook object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildBook The associated ChildBook object.
     * @throws PropelException
     */
    public function getBook(ConnectionInterface $con = null)
    {
        if ($this->aBook === null && ($this->book_id !== null)) {
            $this->aBook = ChildBookQuery::create()->findPk($this->book_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aBook->addVerses($this);
             */
        }

        return $this->aBook;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('Passage' == $relationName) {
            return $this->initPassages();
        }
        if ('Tag' == $relationName) {
            return $this->initTags();
        }
    }

    /**
     * Clears out the collPassages collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPassages()
     */
    public function clearPassages()
    {
        $this->collPassages = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPassages collection loaded partially.
     */
    public function resetPartialPassages($v = true)
    {
        $this->collPassagesPartial = $v;
    }

    /**
     * Initializes the collPassages collection.
     *
     * By default this just sets the collPassages collection to an empty array (like clearcollPassages());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPassages($overrideExisting = true)
    {
        if (null !== $this->collPassages && !$overrideExisting) {
            return;
        }
        $this->collPassages = new ObjectCollection();
        $this->collPassages->setModel('\Passage');
    }

    /**
     * Gets an array of ChildPassage objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildVerse is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildPassage[] List of ChildPassage objects
     * @throws PropelException
     */
    public function getPassages(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPassagesPartial && !$this->isNew();
        if (null === $this->collPassages || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPassages) {
                // return empty collection
                $this->initPassages();
            } else {
                $collPassages = ChildPassageQuery::create(null, $criteria)
                    ->filterByVerse($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPassagesPartial && count($collPassages)) {
                        $this->initPassages(false);

                        foreach ($collPassages as $obj) {
                            if (false == $this->collPassages->contains($obj)) {
                                $this->collPassages->append($obj);
                            }
                        }

                        $this->collPassagesPartial = true;
                    }

                    return $collPassages;
                }

                if ($partial && $this->collPassages) {
                    foreach ($this->collPassages as $obj) {
                        if ($obj->isNew()) {
                            $collPassages[] = $obj;
                        }
                    }
                }

                $this->collPassages = $collPassages;
                $this->collPassagesPartial = false;
            }
        }

        return $this->collPassages;
    }

    /**
     * Sets a collection of ChildPassage objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $passages A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildVerse The current object (for fluent API support)
     */
    public function setPassages(Collection $passages, ConnectionInterface $con = null)
    {
        /** @var ChildPassage[] $passagesToDelete */
        $passagesToDelete = $this->getPassages(new Criteria(), $con)->diff($passages);


        $this->passagesScheduledForDeletion = $passagesToDelete;

        foreach ($passagesToDelete as $passageRemoved) {
            $passageRemoved->setVerse(null);
        }

        $this->collPassages = null;
        foreach ($passages as $passage) {
            $this->addPassage($passage);
        }

        $this->collPassages = $passages;
        $this->collPassagesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Passage objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Passage objects.
     * @throws PropelException
     */
    public function countPassages(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPassagesPartial && !$this->isNew();
        if (null === $this->collPassages || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPassages) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPassages());
            }

            $query = ChildPassageQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByVerse($this)
                ->count($con);
        }

        return count($this->collPassages);
    }

    /**
     * Method called to associate a ChildPassage object to this object
     * through the ChildPassage foreign key attribute.
     *
     * @param  ChildPassage $l ChildPassage
     * @return $this|\Verse The current object (for fluent API support)
     */
    public function addPassage(ChildPassage $l)
    {
        if ($this->collPassages === null) {
            $this->initPassages();
            $this->collPassagesPartial = true;
        }

        if (!$this->collPassages->contains($l)) {
            $this->doAddPassage($l);
        }

        return $this;
    }

    /**
     * @param ChildPassage $passage The ChildPassage object to add.
     */
    protected function doAddPassage(ChildPassage $passage)
    {
        $this->collPassages[]= $passage;
        $passage->setVerse($this);
    }

    /**
     * @param  ChildPassage $passage The ChildPassage object to remove.
     * @return $this|ChildVerse The current object (for fluent API support)
     */
    public function removePassage(ChildPassage $passage)
    {
        if ($this->getPassages()->contains($passage)) {
            $pos = $this->collPassages->search($passage);
            $this->collPassages->remove($pos);
            if (null === $this->passagesScheduledForDeletion) {
                $this->passagesScheduledForDeletion = clone $this->collPassages;
                $this->passagesScheduledForDeletion->clear();
            }
            $this->passagesScheduledForDeletion[]= clone $passage;
            $passage->setVerse(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Verse is new, it will return
     * an empty collection; or if this Verse has previously
     * been saved, it will retrieve related Passages from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Verse.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildPassage[] List of ChildPassage objects
     */
    public function getPassagesJoinBible(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPassageQuery::create(null, $criteria);
        $query->joinWith('Bible', $joinBehavior);

        return $this->getPassages($query, $con);
    }

    /**
     * Clears out the collTags collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addTags()
     */
    public function clearTags()
    {
        $this->collTags = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collTags collection loaded partially.
     */
    public function resetPartialTags($v = true)
    {
        $this->collTagsPartial = $v;
    }

    /**
     * Initializes the collTags collection.
     *
     * By default this just sets the collTags collection to an empty array (like clearcollTags());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initTags($overrideExisting = true)
    {
        if (null !== $this->collTags && !$overrideExisting) {
            return;
        }
        $this->collTags = new ObjectCollection();
        $this->collTags->setModel('\Tag');
    }

    /**
     * Gets an array of ChildTag objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildVerse is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildTag[] List of ChildTag objects
     * @throws PropelException
     */
    public function getTags(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collTagsPartial && !$this->isNew();
        if (null === $this->collTags || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collTags) {
                // return empty collection
                $this->initTags();
            } else {
                $collTags = ChildTagQuery::create(null, $criteria)
                    ->filterByVerse($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collTagsPartial && count($collTags)) {
                        $this->initTags(false);

                        foreach ($collTags as $obj) {
                            if (false == $this->collTags->contains($obj)) {
                                $this->collTags->append($obj);
                            }
                        }

                        $this->collTagsPartial = true;
                    }

                    return $collTags;
                }

                if ($partial && $this->collTags) {
                    foreach ($this->collTags as $obj) {
                        if ($obj->isNew()) {
                            $collTags[] = $obj;
                        }
                    }
                }

                $this->collTags = $collTags;
                $this->collTagsPartial = false;
            }
        }

        return $this->collTags;
    }

    /**
     * Sets a collection of ChildTag objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $tags A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildVerse The current object (for fluent API support)
     */
    public function setTags(Collection $tags, ConnectionInterface $con = null)
    {
        /** @var ChildTag[] $tagsToDelete */
        $tagsToDelete = $this->getTags(new Criteria(), $con)->diff($tags);


        $this->tagsScheduledForDeletion = $tagsToDelete;

        foreach ($tagsToDelete as $tagRemoved) {
            $tagRemoved->setVerse(null);
        }

        $this->collTags = null;
        foreach ($tags as $tag) {
            $this->addTag($tag);
        }

        $this->collTags = $tags;
        $this->collTagsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Tag objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Tag objects.
     * @throws PropelException
     */
    public function countTags(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collTagsPartial && !$this->isNew();
        if (null === $this->collTags || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collTags) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getTags());
            }

            $query = ChildTagQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByVerse($this)
                ->count($con);
        }

        return count($this->collTags);
    }

    /**
     * Method called to associate a ChildTag object to this object
     * through the ChildTag foreign key attribute.
     *
     * @param  ChildTag $l ChildTag
     * @return $this|\Verse The current object (for fluent API support)
     */
    public function addTag(ChildTag $l)
    {
        if ($this->collTags === null) {
            $this->initTags();
            $this->collTagsPartial = true;
        }

        if (!$this->collTags->contains($l)) {
            $this->doAddTag($l);
        }

        return $this;
    }

    /**
     * @param ChildTag $tag The ChildTag object to add.
     */
    protected function doAddTag(ChildTag $tag)
    {
        $this->collTags[]= $tag;
        $tag->setVerse($this);
    }

    /**
     * @param  ChildTag $tag The ChildTag object to remove.
     * @return $this|ChildVerse The current object (for fluent API support)
     */
    public function removeTag(ChildTag $tag)
    {
        if ($this->getTags()->contains($tag)) {
            $pos = $this->collTags->search($tag);
            $this->collTags->remove($pos);
            if (null === $this->tagsScheduledForDeletion) {
                $this->tagsScheduledForDeletion = clone $this->collTags;
                $this->tagsScheduledForDeletion->clear();
            }
            $this->tagsScheduledForDeletion[]= clone $tag;
            $tag->setVerse(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Verse is new, it will return
     * an empty collection; or if this Verse has previously
     * been saved, it will retrieve related Tags from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Verse.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildTag[] List of ChildTag objects
     */
    public function getTagsJoinKeyword(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildTagQuery::create(null, $criteria);
        $query->joinWith('Keyword', $joinBehavior);

        return $this->getTags($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Verse is new, it will return
     * an empty collection; or if this Verse has previously
     * been saved, it will retrieve related Tags from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Verse.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildTag[] List of ChildTag objects
     */
    public function getTagsJoinTagType(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildTagQuery::create(null, $criteria);
        $query->joinWith('TagType', $joinBehavior);

        return $this->getTags($query, $con);
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aBook) {
            $this->aBook->removeVerse($this);
        }
        $this->book_id = null;
        $this->chapter_number = null;
        $this->verse_number = null;
        $this->id = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collPassages) {
                foreach ($this->collPassages as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collTags) {
                foreach ($this->collTags as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collPassages = null;
        $this->collTags = null;
        $this->aBook = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(VerseTableMap::DEFAULT_STRING_FORMAT);
    }

    // aggregate_column_relation_number_of_chapters_aggregate behavior

    /**
     * Update the aggregate column in the related Book object
     *
     * @param ConnectionInterface $con A connection object
     */
    protected function updateRelatedBookNumberOfChapters(ConnectionInterface $con)
    {
        if ($book = $this->getBook()) {
            $book->updateNumberOfChapters($con);
        }
        if ($this->oldBookNumberOfChapters) {
            $this->oldBookNumberOfChapters->updateNumberOfChapters($con);
            $this->oldBookNumberOfChapters = null;
        }
    }

    // aggregate_column_relation_number_of_verses_aggregate behavior

    /**
     * Update the aggregate column in the related Book object
     *
     * @param ConnectionInterface $con A connection object
     */
    protected function updateRelatedBookNumberOfVerses(ConnectionInterface $con)
    {
        if ($book = $this->getBook()) {
            $book->updateNumberOfVerses($con);
        }
        if ($this->oldBookNumberOfVerses) {
            $this->oldBookNumberOfVerses->updateNumberOfVerses($con);
            $this->oldBookNumberOfVerses = null;
        }
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {

    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
