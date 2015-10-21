<?php

namespace Base;

use \Passage as ChildPassage;
use \PassageQuery as ChildPassageQuery;
use \Exception;
use \PDO;
use Map\PassageTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'passage' table.
 *
 *
 *
 * @method     ChildPassageQuery orderByBibleId($order = Criteria::ASC) Order by the bible_id column
 * @method     ChildPassageQuery orderByText($order = Criteria::ASC) Order by the text column
 * @method     ChildPassageQuery orderByVerseId($order = Criteria::ASC) Order by the verse_id column
 * @method     ChildPassageQuery orderById($order = Criteria::ASC) Order by the id column
 *
 * @method     ChildPassageQuery groupByBibleId() Group by the bible_id column
 * @method     ChildPassageQuery groupByText() Group by the text column
 * @method     ChildPassageQuery groupByVerseId() Group by the verse_id column
 * @method     ChildPassageQuery groupById() Group by the id column
 *
 * @method     ChildPassageQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPassageQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPassageQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPassageQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildPassageQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildPassageQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildPassageQuery leftJoinBible($relationAlias = null) Adds a LEFT JOIN clause to the query using the Bible relation
 * @method     ChildPassageQuery rightJoinBible($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Bible relation
 * @method     ChildPassageQuery innerJoinBible($relationAlias = null) Adds a INNER JOIN clause to the query using the Bible relation
 *
 * @method     ChildPassageQuery joinWithBible($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Bible relation
 *
 * @method     ChildPassageQuery leftJoinWithBible() Adds a LEFT JOIN clause and with to the query using the Bible relation
 * @method     ChildPassageQuery rightJoinWithBible() Adds a RIGHT JOIN clause and with to the query using the Bible relation
 * @method     ChildPassageQuery innerJoinWithBible() Adds a INNER JOIN clause and with to the query using the Bible relation
 *
 * @method     ChildPassageQuery leftJoinVerse($relationAlias = null) Adds a LEFT JOIN clause to the query using the Verse relation
 * @method     ChildPassageQuery rightJoinVerse($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Verse relation
 * @method     ChildPassageQuery innerJoinVerse($relationAlias = null) Adds a INNER JOIN clause to the query using the Verse relation
 *
 * @method     ChildPassageQuery joinWithVerse($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Verse relation
 *
 * @method     ChildPassageQuery leftJoinWithVerse() Adds a LEFT JOIN clause and with to the query using the Verse relation
 * @method     ChildPassageQuery rightJoinWithVerse() Adds a RIGHT JOIN clause and with to the query using the Verse relation
 * @method     ChildPassageQuery innerJoinWithVerse() Adds a INNER JOIN clause and with to the query using the Verse relation
 *
 * @method     \BibleQuery|\VerseQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildPassage findOne(ConnectionInterface $con = null) Return the first ChildPassage matching the query
 * @method     ChildPassage findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPassage matching the query, or a new ChildPassage object populated from the query conditions when no match is found
 *
 * @method     ChildPassage findOneByBibleId(int $bible_id) Return the first ChildPassage filtered by the bible_id column
 * @method     ChildPassage findOneByText(string $text) Return the first ChildPassage filtered by the text column
 * @method     ChildPassage findOneByVerseId(int $verse_id) Return the first ChildPassage filtered by the verse_id column
 * @method     ChildPassage findOneById(int $id) Return the first ChildPassage filtered by the id column *

 * @method     ChildPassage requirePk($key, ConnectionInterface $con = null) Return the ChildPassage by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPassage requireOne(ConnectionInterface $con = null) Return the first ChildPassage matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPassage requireOneByBibleId(int $bible_id) Return the first ChildPassage filtered by the bible_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPassage requireOneByText(string $text) Return the first ChildPassage filtered by the text column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPassage requireOneByVerseId(int $verse_id) Return the first ChildPassage filtered by the verse_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildPassage requireOneById(int $id) Return the first ChildPassage filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildPassage[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildPassage objects based on current ModelCriteria
 * @method     ChildPassage[]|ObjectCollection findByBibleId(int $bible_id) Return ChildPassage objects filtered by the bible_id column
 * @method     ChildPassage[]|ObjectCollection findByText(string $text) Return ChildPassage objects filtered by the text column
 * @method     ChildPassage[]|ObjectCollection findByVerseId(int $verse_id) Return ChildPassage objects filtered by the verse_id column
 * @method     ChildPassage[]|ObjectCollection findById(int $id) Return ChildPassage objects filtered by the id column
 * @method     ChildPassage[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class PassageQuery extends ModelCriteria
{

    // delegate behavior

    protected $delegatedFields = [
        'BookId' => 'Verse',
        'ChapterNumber' => 'Verse',
        'VerseNumber' => 'Verse',
    ];

protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\PassageQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Passage', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPassageQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPassageQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildPassageQuery) {
            return $criteria;
        }
        $query = new ChildPassageQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildPassage|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PassageTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PassageTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPassage A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT bible_id, text, verse_id, id FROM passage WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildPassage $obj */
            $obj = new ChildPassage();
            $obj->hydrate($row);
            PassageTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildPassage|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildPassageQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PassageTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildPassageQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PassageTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the bible_id column
     *
     * Example usage:
     * <code>
     * $query->filterByBibleId(1234); // WHERE bible_id = 1234
     * $query->filterByBibleId(array(12, 34)); // WHERE bible_id IN (12, 34)
     * $query->filterByBibleId(array('min' => 12)); // WHERE bible_id > 12
     * </code>
     *
     * @see       filterByBible()
     *
     * @param     mixed $bibleId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPassageQuery The current query, for fluid interface
     */
    public function filterByBibleId($bibleId = null, $comparison = null)
    {
        if (is_array($bibleId)) {
            $useMinMax = false;
            if (isset($bibleId['min'])) {
                $this->addUsingAlias(PassageTableMap::COL_BIBLE_ID, $bibleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($bibleId['max'])) {
                $this->addUsingAlias(PassageTableMap::COL_BIBLE_ID, $bibleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PassageTableMap::COL_BIBLE_ID, $bibleId, $comparison);
    }

    /**
     * Filter the query on the text column
     *
     * Example usage:
     * <code>
     * $query->filterByText('fooValue');   // WHERE text = 'fooValue'
     * $query->filterByText('%fooValue%'); // WHERE text LIKE '%fooValue%'
     * </code>
     *
     * @param     string $text The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPassageQuery The current query, for fluid interface
     */
    public function filterByText($text = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($text)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $text)) {
                $text = str_replace('*', '%', $text);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PassageTableMap::COL_TEXT, $text, $comparison);
    }

    /**
     * Filter the query on the verse_id column
     *
     * Example usage:
     * <code>
     * $query->filterByVerseId(1234); // WHERE verse_id = 1234
     * $query->filterByVerseId(array(12, 34)); // WHERE verse_id IN (12, 34)
     * $query->filterByVerseId(array('min' => 12)); // WHERE verse_id > 12
     * </code>
     *
     * @see       filterByVerse()
     *
     * @param     mixed $verseId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPassageQuery The current query, for fluid interface
     */
    public function filterByVerseId($verseId = null, $comparison = null)
    {
        if (is_array($verseId)) {
            $useMinMax = false;
            if (isset($verseId['min'])) {
                $this->addUsingAlias(PassageTableMap::COL_VERSE_ID, $verseId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($verseId['max'])) {
                $this->addUsingAlias(PassageTableMap::COL_VERSE_ID, $verseId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PassageTableMap::COL_VERSE_ID, $verseId, $comparison);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildPassageQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PassageTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PassageTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PassageTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query by a related \Bible object
     *
     * @param \Bible|ObjectCollection $bible The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPassageQuery The current query, for fluid interface
     */
    public function filterByBible($bible, $comparison = null)
    {
        if ($bible instanceof \Bible) {
            return $this
                ->addUsingAlias(PassageTableMap::COL_BIBLE_ID, $bible->getId(), $comparison);
        } elseif ($bible instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PassageTableMap::COL_BIBLE_ID, $bible->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByBible() only accepts arguments of type \Bible or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Bible relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPassageQuery The current query, for fluid interface
     */
    public function joinBible($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Bible');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Bible');
        }

        return $this;
    }

    /**
     * Use the Bible relation Bible object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \BibleQuery A secondary query class using the current class as primary query
     */
    public function useBibleQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinBible($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Bible', '\BibleQuery');
    }

    /**
     * Filter the query by a related \Verse object
     *
     * @param \Verse|ObjectCollection $verse The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildPassageQuery The current query, for fluid interface
     */
    public function filterByVerse($verse, $comparison = null)
    {
        if ($verse instanceof \Verse) {
            return $this
                ->addUsingAlias(PassageTableMap::COL_VERSE_ID, $verse->getId(), $comparison);
        } elseif ($verse instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PassageTableMap::COL_VERSE_ID, $verse->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByVerse() only accepts arguments of type \Verse or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Verse relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildPassageQuery The current query, for fluid interface
     */
    public function joinVerse($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Verse');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Verse');
        }

        return $this;
    }

    /**
     * Use the Verse relation Verse object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \VerseQuery A secondary query class using the current class as primary query
     */
    public function useVerseQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinVerse($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Verse', '\VerseQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPassage $passage Object to remove from the list of results
     *
     * @return $this|ChildPassageQuery The current query, for fluid interface
     */
    public function prune($passage = null)
    {
        if ($passage) {
            $this->addUsingAlias(PassageTableMap::COL_ID, $passage->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the passage table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PassageTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PassageTableMap::clearInstancePool();
            PassageTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PassageTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PassageTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            PassageTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PassageTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // delegate behavior
    /**
    * Filter the query by book_id column
    *
    * Example usage:
    * <code>
        * $query->filterByBookId(1234); // WHERE book_id = 1234
        * $query->filterByBookId(array(12, 34)); // WHERE book_id IN (12, 34)
        * $query->filterByBookId(array('min' => 12)); // WHERE book_id > 12
        * </code>
    *
    * @param     mixed $value The value to use as filter.
    *              Use scalar values for equality.
    *              Use array values for in_array() equivalent.
    *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
    * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
    *
    * @return $this|ChildPassageQuery The current query, for fluid interface
    */
    public function filterByBookId($value = null, $comparison = null)
    {
        return $this->useVerseQuery()->filterByBookId($value, $comparison)->endUse();
    }

    /**
    * Adds an ORDER BY clause to the query
    * Usability layer on top of Criteria::addAscendingOrderByColumn() and Criteria::addDescendingOrderByColumn()
    * Infers $column and $order from $columnName and some optional arguments
    * Examples:
    *   $c->orderBy('Book.CreatedAt')
    *    => $c->addAscendingOrderByColumn(BookTableMap::CREATED_AT)
    *   $c->orderBy('Book.CategoryId', 'desc')
    *    => $c->addDescendingOrderByColumn(BookTableMap::CATEGORY_ID)
    *
    * @param string $order      The sorting order. Criteria::ASC by default, also accepts Criteria::DESC
    *
    * @return $this|ModelCriteria The current object, for fluid interface
    */
    public function orderByBookId($order = Criteria::ASC)
    {
        return $this->useVerseQuery()->orderByBookId($order)->endUse();
    }
    /**
    * Filter the query by chapter_number column
    *
    * Example usage:
    * <code>
        * $query->filterByChapterNumber(1234); // WHERE chapter_number = 1234
        * $query->filterByChapterNumber(array(12, 34)); // WHERE chapter_number IN (12, 34)
        * $query->filterByChapterNumber(array('min' => 12)); // WHERE chapter_number > 12
        * </code>
    *
    * @param     mixed $value The value to use as filter.
    *              Use scalar values for equality.
    *              Use array values for in_array() equivalent.
    *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
    * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
    *
    * @return $this|ChildPassageQuery The current query, for fluid interface
    */
    public function filterByChapterNumber($value = null, $comparison = null)
    {
        return $this->useVerseQuery()->filterByChapterNumber($value, $comparison)->endUse();
    }

    /**
    * Adds an ORDER BY clause to the query
    * Usability layer on top of Criteria::addAscendingOrderByColumn() and Criteria::addDescendingOrderByColumn()
    * Infers $column and $order from $columnName and some optional arguments
    * Examples:
    *   $c->orderBy('Book.CreatedAt')
    *    => $c->addAscendingOrderByColumn(BookTableMap::CREATED_AT)
    *   $c->orderBy('Book.CategoryId', 'desc')
    *    => $c->addDescendingOrderByColumn(BookTableMap::CATEGORY_ID)
    *
    * @param string $order      The sorting order. Criteria::ASC by default, also accepts Criteria::DESC
    *
    * @return $this|ModelCriteria The current object, for fluid interface
    */
    public function orderByChapterNumber($order = Criteria::ASC)
    {
        return $this->useVerseQuery()->orderByChapterNumber($order)->endUse();
    }
    /**
    * Filter the query by verse_number column
    *
    * Example usage:
    * <code>
        * $query->filterByVerseNumber(1234); // WHERE verse_number = 1234
        * $query->filterByVerseNumber(array(12, 34)); // WHERE verse_number IN (12, 34)
        * $query->filterByVerseNumber(array('min' => 12)); // WHERE verse_number > 12
        * </code>
    *
    * @param     mixed $value The value to use as filter.
    *              Use scalar values for equality.
    *              Use array values for in_array() equivalent.
    *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
    * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
    *
    * @return $this|ChildPassageQuery The current query, for fluid interface
    */
    public function filterByVerseNumber($value = null, $comparison = null)
    {
        return $this->useVerseQuery()->filterByVerseNumber($value, $comparison)->endUse();
    }

    /**
    * Adds an ORDER BY clause to the query
    * Usability layer on top of Criteria::addAscendingOrderByColumn() and Criteria::addDescendingOrderByColumn()
    * Infers $column and $order from $columnName and some optional arguments
    * Examples:
    *   $c->orderBy('Book.CreatedAt')
    *    => $c->addAscendingOrderByColumn(BookTableMap::CREATED_AT)
    *   $c->orderBy('Book.CategoryId', 'desc')
    *    => $c->addDescendingOrderByColumn(BookTableMap::CATEGORY_ID)
    *
    * @param string $order      The sorting order. Criteria::ASC by default, also accepts Criteria::DESC
    *
    * @return $this|ModelCriteria The current object, for fluid interface
    */
    public function orderByVerseNumber($order = Criteria::ASC)
    {
        return $this->useVerseQuery()->orderByVerseNumber($order)->endUse();
    }

    /**
     * Adds a condition on a column based on a column phpName and a value
     * Uses introspection to translate the column phpName into a fully qualified name
     * Warning: recognizes only the phpNames of the main Model (not joined tables)
     * <code>
     * $c->filterBy('Title', 'foo');
     * </code>
     *
     * @see Criteria::add()
     *
     * @param string $column     A string representing thecolumn phpName, e.g. 'AuthorId'
     * @param mixed  $value      A value for the condition
     * @param string $comparison What to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ModelCriteria The current object, for fluid interface
     */
    public function filterBy($column, $value, $comparison = Criteria::EQUAL)
    {
        if (isset($this->delegatedFields[$column])) {
            $methodUse = "use{$this->delegatedFields[$column]}Query";

            return $this->{$methodUse}()->filterBy($column, $value, $comparison)->endUse();
        } else {
            return $this->add($this->getRealColumnName($column), $value, $comparison);
        }
    }

} // PassageQuery
