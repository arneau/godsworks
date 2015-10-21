<?php

namespace Base;

use \Verse as ChildVerse;
use \VerseQuery as ChildVerseQuery;
use \Exception;
use \PDO;
use Map\VerseTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'verse' table.
 *
 *
 *
 * @method     ChildVerseQuery orderByBookId($order = Criteria::ASC) Order by the book_id column
 * @method     ChildVerseQuery orderByChapterNumber($order = Criteria::ASC) Order by the chapter_number column
 * @method     ChildVerseQuery orderByVerseNumber($order = Criteria::ASC) Order by the verse_number column
 * @method     ChildVerseQuery orderById($order = Criteria::ASC) Order by the id column
 *
 * @method     ChildVerseQuery groupByBookId() Group by the book_id column
 * @method     ChildVerseQuery groupByChapterNumber() Group by the chapter_number column
 * @method     ChildVerseQuery groupByVerseNumber() Group by the verse_number column
 * @method     ChildVerseQuery groupById() Group by the id column
 *
 * @method     ChildVerseQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildVerseQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildVerseQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildVerseQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildVerseQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildVerseQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildVerseQuery leftJoinBook($relationAlias = null) Adds a LEFT JOIN clause to the query using the Book relation
 * @method     ChildVerseQuery rightJoinBook($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Book relation
 * @method     ChildVerseQuery innerJoinBook($relationAlias = null) Adds a INNER JOIN clause to the query using the Book relation
 *
 * @method     ChildVerseQuery joinWithBook($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Book relation
 *
 * @method     ChildVerseQuery leftJoinWithBook() Adds a LEFT JOIN clause and with to the query using the Book relation
 * @method     ChildVerseQuery rightJoinWithBook() Adds a RIGHT JOIN clause and with to the query using the Book relation
 * @method     ChildVerseQuery innerJoinWithBook() Adds a INNER JOIN clause and with to the query using the Book relation
 *
 * @method     ChildVerseQuery leftJoinPassage($relationAlias = null) Adds a LEFT JOIN clause to the query using the Passage relation
 * @method     ChildVerseQuery rightJoinPassage($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Passage relation
 * @method     ChildVerseQuery innerJoinPassage($relationAlias = null) Adds a INNER JOIN clause to the query using the Passage relation
 *
 * @method     ChildVerseQuery joinWithPassage($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Passage relation
 *
 * @method     ChildVerseQuery leftJoinWithPassage() Adds a LEFT JOIN clause and with to the query using the Passage relation
 * @method     ChildVerseQuery rightJoinWithPassage() Adds a RIGHT JOIN clause and with to the query using the Passage relation
 * @method     ChildVerseQuery innerJoinWithPassage() Adds a INNER JOIN clause and with to the query using the Passage relation
 *
 * @method     ChildVerseQuery leftJoinTag($relationAlias = null) Adds a LEFT JOIN clause to the query using the Tag relation
 * @method     ChildVerseQuery rightJoinTag($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Tag relation
 * @method     ChildVerseQuery innerJoinTag($relationAlias = null) Adds a INNER JOIN clause to the query using the Tag relation
 *
 * @method     ChildVerseQuery joinWithTag($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Tag relation
 *
 * @method     ChildVerseQuery leftJoinWithTag() Adds a LEFT JOIN clause and with to the query using the Tag relation
 * @method     ChildVerseQuery rightJoinWithTag() Adds a RIGHT JOIN clause and with to the query using the Tag relation
 * @method     ChildVerseQuery innerJoinWithTag() Adds a INNER JOIN clause and with to the query using the Tag relation
 *
 * @method     \BookQuery|\PassageQuery|\TagQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildVerse findOne(ConnectionInterface $con = null) Return the first ChildVerse matching the query
 * @method     ChildVerse findOneOrCreate(ConnectionInterface $con = null) Return the first ChildVerse matching the query, or a new ChildVerse object populated from the query conditions when no match is found
 *
 * @method     ChildVerse findOneByBookId(int $book_id) Return the first ChildVerse filtered by the book_id column
 * @method     ChildVerse findOneByChapterNumber(int $chapter_number) Return the first ChildVerse filtered by the chapter_number column
 * @method     ChildVerse findOneByVerseNumber(int $verse_number) Return the first ChildVerse filtered by the verse_number column
 * @method     ChildVerse findOneById(int $id) Return the first ChildVerse filtered by the id column *

 * @method     ChildVerse requirePk($key, ConnectionInterface $con = null) Return the ChildVerse by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildVerse requireOne(ConnectionInterface $con = null) Return the first ChildVerse matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildVerse requireOneByBookId(int $book_id) Return the first ChildVerse filtered by the book_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildVerse requireOneByChapterNumber(int $chapter_number) Return the first ChildVerse filtered by the chapter_number column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildVerse requireOneByVerseNumber(int $verse_number) Return the first ChildVerse filtered by the verse_number column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildVerse requireOneById(int $id) Return the first ChildVerse filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildVerse[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildVerse objects based on current ModelCriteria
 * @method     ChildVerse[]|ObjectCollection findByBookId(int $book_id) Return ChildVerse objects filtered by the book_id column
 * @method     ChildVerse[]|ObjectCollection findByChapterNumber(int $chapter_number) Return ChildVerse objects filtered by the chapter_number column
 * @method     ChildVerse[]|ObjectCollection findByVerseNumber(int $verse_number) Return ChildVerse objects filtered by the verse_number column
 * @method     ChildVerse[]|ObjectCollection findById(int $id) Return ChildVerse objects filtered by the id column
 * @method     ChildVerse[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class VerseQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Base\VerseQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Verse', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildVerseQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildVerseQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildVerseQuery) {
            return $criteria;
        }
        $query = new ChildVerseQuery();
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
     * @return ChildVerse|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = VerseTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(VerseTableMap::DATABASE_NAME);
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
     * @return ChildVerse A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT book_id, chapter_number, verse_number, id FROM verse WHERE id = :p0';
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
            /** @var ChildVerse $obj */
            $obj = new ChildVerse();
            $obj->hydrate($row);
            VerseTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildVerse|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildVerseQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(VerseTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildVerseQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(VerseTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the book_id column
     *
     * Example usage:
     * <code>
     * $query->filterByBookId(1234); // WHERE book_id = 1234
     * $query->filterByBookId(array(12, 34)); // WHERE book_id IN (12, 34)
     * $query->filterByBookId(array('min' => 12)); // WHERE book_id > 12
     * </code>
     *
     * @see       filterByBook()
     *
     * @param     mixed $bookId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildVerseQuery The current query, for fluid interface
     */
    public function filterByBookId($bookId = null, $comparison = null)
    {
        if (is_array($bookId)) {
            $useMinMax = false;
            if (isset($bookId['min'])) {
                $this->addUsingAlias(VerseTableMap::COL_BOOK_ID, $bookId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($bookId['max'])) {
                $this->addUsingAlias(VerseTableMap::COL_BOOK_ID, $bookId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(VerseTableMap::COL_BOOK_ID, $bookId, $comparison);
    }

    /**
     * Filter the query on the chapter_number column
     *
     * Example usage:
     * <code>
     * $query->filterByChapterNumber(1234); // WHERE chapter_number = 1234
     * $query->filterByChapterNumber(array(12, 34)); // WHERE chapter_number IN (12, 34)
     * $query->filterByChapterNumber(array('min' => 12)); // WHERE chapter_number > 12
     * </code>
     *
     * @param     mixed $chapterNumber The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildVerseQuery The current query, for fluid interface
     */
    public function filterByChapterNumber($chapterNumber = null, $comparison = null)
    {
        if (is_array($chapterNumber)) {
            $useMinMax = false;
            if (isset($chapterNumber['min'])) {
                $this->addUsingAlias(VerseTableMap::COL_CHAPTER_NUMBER, $chapterNumber['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($chapterNumber['max'])) {
                $this->addUsingAlias(VerseTableMap::COL_CHAPTER_NUMBER, $chapterNumber['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(VerseTableMap::COL_CHAPTER_NUMBER, $chapterNumber, $comparison);
    }

    /**
     * Filter the query on the verse_number column
     *
     * Example usage:
     * <code>
     * $query->filterByVerseNumber(1234); // WHERE verse_number = 1234
     * $query->filterByVerseNumber(array(12, 34)); // WHERE verse_number IN (12, 34)
     * $query->filterByVerseNumber(array('min' => 12)); // WHERE verse_number > 12
     * </code>
     *
     * @param     mixed $verseNumber The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildVerseQuery The current query, for fluid interface
     */
    public function filterByVerseNumber($verseNumber = null, $comparison = null)
    {
        if (is_array($verseNumber)) {
            $useMinMax = false;
            if (isset($verseNumber['min'])) {
                $this->addUsingAlias(VerseTableMap::COL_VERSE_NUMBER, $verseNumber['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($verseNumber['max'])) {
                $this->addUsingAlias(VerseTableMap::COL_VERSE_NUMBER, $verseNumber['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(VerseTableMap::COL_VERSE_NUMBER, $verseNumber, $comparison);
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
     * @return $this|ChildVerseQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(VerseTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(VerseTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(VerseTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query by a related \Book object
     *
     * @param \Book|ObjectCollection $book The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildVerseQuery The current query, for fluid interface
     */
    public function filterByBook($book, $comparison = null)
    {
        if ($book instanceof \Book) {
            return $this
                ->addUsingAlias(VerseTableMap::COL_BOOK_ID, $book->getId(), $comparison);
        } elseif ($book instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(VerseTableMap::COL_BOOK_ID, $book->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByBook() only accepts arguments of type \Book or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Book relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildVerseQuery The current query, for fluid interface
     */
    public function joinBook($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Book');

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
            $this->addJoinObject($join, 'Book');
        }

        return $this;
    }

    /**
     * Use the Book relation Book object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \BookQuery A secondary query class using the current class as primary query
     */
    public function useBookQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinBook($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Book', '\BookQuery');
    }

    /**
     * Filter the query by a related \Passage object
     *
     * @param \Passage|ObjectCollection $passage the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildVerseQuery The current query, for fluid interface
     */
    public function filterByPassage($passage, $comparison = null)
    {
        if ($passage instanceof \Passage) {
            return $this
                ->addUsingAlias(VerseTableMap::COL_ID, $passage->getVerseId(), $comparison);
        } elseif ($passage instanceof ObjectCollection) {
            return $this
                ->usePassageQuery()
                ->filterByPrimaryKeys($passage->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPassage() only accepts arguments of type \Passage or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Passage relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildVerseQuery The current query, for fluid interface
     */
    public function joinPassage($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Passage');

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
            $this->addJoinObject($join, 'Passage');
        }

        return $this;
    }

    /**
     * Use the Passage relation Passage object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \PassageQuery A secondary query class using the current class as primary query
     */
    public function usePassageQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPassage($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Passage', '\PassageQuery');
    }

    /**
     * Filter the query by a related \Tag object
     *
     * @param \Tag|ObjectCollection $tag the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildVerseQuery The current query, for fluid interface
     */
    public function filterByTag($tag, $comparison = null)
    {
        if ($tag instanceof \Tag) {
            return $this
                ->addUsingAlias(VerseTableMap::COL_ID, $tag->getVerseId(), $comparison);
        } elseif ($tag instanceof ObjectCollection) {
            return $this
                ->useTagQuery()
                ->filterByPrimaryKeys($tag->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByTag() only accepts arguments of type \Tag or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Tag relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildVerseQuery The current query, for fluid interface
     */
    public function joinTag($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Tag');

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
            $this->addJoinObject($join, 'Tag');
        }

        return $this;
    }

    /**
     * Use the Tag relation Tag object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \TagQuery A secondary query class using the current class as primary query
     */
    public function useTagQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinTag($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Tag', '\TagQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildVerse $verse Object to remove from the list of results
     *
     * @return $this|ChildVerseQuery The current query, for fluid interface
     */
    public function prune($verse = null)
    {
        if ($verse) {
            $this->addUsingAlias(VerseTableMap::COL_ID, $verse->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Code to execute before every DELETE statement
     *
     * @param     ConnectionInterface $con The connection object used by the query
     */
    protected function basePreDelete(ConnectionInterface $con)
    {
        // aggregate_column_relation_number_of_chapters_aggregate behavior
        $this->findRelatedBookNumberOfChapterss($con);
        // aggregate_column_relation_number_of_verses_aggregate behavior
        $this->findRelatedBookNumberOfVersess($con);

        return $this->preDelete($con);
    }

    /**
     * Code to execute after every DELETE statement
     *
     * @param     int $affectedRows the number of deleted rows
     * @param     ConnectionInterface $con The connection object used by the query
     */
    protected function basePostDelete($affectedRows, ConnectionInterface $con)
    {
        // aggregate_column_relation_number_of_chapters_aggregate behavior
        $this->updateRelatedBookNumberOfChapterss($con);
        // aggregate_column_relation_number_of_verses_aggregate behavior
        $this->updateRelatedBookNumberOfVersess($con);

        return $this->postDelete($affectedRows, $con);
    }

    /**
     * Code to execute before every UPDATE statement
     *
     * @param     array $values The associative array of columns and values for the update
     * @param     ConnectionInterface $con The connection object used by the query
     * @param     boolean $forceIndividualSaves If false (default), the resulting call is a Criteria::doUpdate(), otherwise it is a series of save() calls on all the found objects
     */
    protected function basePreUpdate(&$values, ConnectionInterface $con, $forceIndividualSaves = false)
    {
        // aggregate_column_relation_number_of_chapters_aggregate behavior
        $this->findRelatedBookNumberOfChapterss($con);
        // aggregate_column_relation_number_of_verses_aggregate behavior
        $this->findRelatedBookNumberOfVersess($con);

        return $this->preUpdate($values, $con, $forceIndividualSaves);
    }

    /**
     * Code to execute after every UPDATE statement
     *
     * @param     int $affectedRows the number of updated rows
     * @param     ConnectionInterface $con The connection object used by the query
     */
    protected function basePostUpdate($affectedRows, ConnectionInterface $con)
    {
        // aggregate_column_relation_number_of_chapters_aggregate behavior
        $this->updateRelatedBookNumberOfChapterss($con);
        // aggregate_column_relation_number_of_verses_aggregate behavior
        $this->updateRelatedBookNumberOfVersess($con);

        return $this->postUpdate($affectedRows, $con);
    }

    /**
     * Deletes all rows from the verse table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(VerseTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            VerseTableMap::clearInstancePool();
            VerseTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(VerseTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(VerseTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            VerseTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            VerseTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // aggregate_column_relation_number_of_chapters_aggregate behavior

    /**
     * Finds the related Book objects and keep them for later
     *
     * @param ConnectionInterface $con A connection object
     */
    protected function findRelatedBookNumberOfChapterss($con)
    {
        $criteria = clone $this;
        if ($this->useAliasInSQL) {
            $alias = $this->getModelAlias();
            $criteria->removeAlias($alias);
        } else {
            $alias = '';
        }
        $this->bookNumberOfChapterss = \BookQuery::create()
            ->joinVerse($alias)
            ->mergeWith($criteria)
            ->find($con);
    }

    protected function updateRelatedBookNumberOfChapterss($con)
    {
        foreach ($this->bookNumberOfChapterss as $bookNumberOfChapters) {
            $bookNumberOfChapters->updateNumberOfChapters($con);
        }
        $this->bookNumberOfChapterss = array();
    }

    // aggregate_column_relation_number_of_verses_aggregate behavior

    /**
     * Finds the related Book objects and keep them for later
     *
     * @param ConnectionInterface $con A connection object
     */
    protected function findRelatedBookNumberOfVersess($con)
    {
        $criteria = clone $this;
        if ($this->useAliasInSQL) {
            $alias = $this->getModelAlias();
            $criteria->removeAlias($alias);
        } else {
            $alias = '';
        }
        $this->bookNumberOfVersess = \BookQuery::create()
            ->joinVerse($alias)
            ->mergeWith($criteria)
            ->find($con);
    }

    protected function updateRelatedBookNumberOfVersess($con)
    {
        foreach ($this->bookNumberOfVersess as $bookNumberOfVerses) {
            $bookNumberOfVerses->updateNumberOfVerses($con);
        }
        $this->bookNumberOfVersess = array();
    }

} // VerseQuery
