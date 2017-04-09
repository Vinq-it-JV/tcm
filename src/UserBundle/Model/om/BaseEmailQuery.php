<?php

namespace UserBundle\Model\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use CompanyBundle\Model\Company;
use CompanyBundle\Model\CompanyEmail;
use StoreBundle\Model\Store;
use StoreBundle\Model\StoreEmail;
use UserBundle\Model\Email;
use UserBundle\Model\EmailPeer;
use UserBundle\Model\EmailQuery;
use UserBundle\Model\User;
use UserBundle\Model\UserEmail;

/**
 * @method EmailQuery orderById($order = Criteria::ASC) Order by the id column
 * @method EmailQuery orderByPrimary($order = Criteria::ASC) Order by the primary column
 * @method EmailQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method EmailQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method EmailQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method EmailQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method EmailQuery groupById() Group by the id column
 * @method EmailQuery groupByPrimary() Group by the primary column
 * @method EmailQuery groupByEmail() Group by the email column
 * @method EmailQuery groupByDescription() Group by the description column
 * @method EmailQuery groupByCreatedAt() Group by the created_at column
 * @method EmailQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method EmailQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method EmailQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method EmailQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method EmailQuery leftJoinCompanyEmail($relationAlias = null) Adds a LEFT JOIN clause to the query using the CompanyEmail relation
 * @method EmailQuery rightJoinCompanyEmail($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CompanyEmail relation
 * @method EmailQuery innerJoinCompanyEmail($relationAlias = null) Adds a INNER JOIN clause to the query using the CompanyEmail relation
 *
 * @method EmailQuery leftJoinStoreEmail($relationAlias = null) Adds a LEFT JOIN clause to the query using the StoreEmail relation
 * @method EmailQuery rightJoinStoreEmail($relationAlias = null) Adds a RIGHT JOIN clause to the query using the StoreEmail relation
 * @method EmailQuery innerJoinStoreEmail($relationAlias = null) Adds a INNER JOIN clause to the query using the StoreEmail relation
 *
 * @method EmailQuery leftJoinUserEmail($relationAlias = null) Adds a LEFT JOIN clause to the query using the UserEmail relation
 * @method EmailQuery rightJoinUserEmail($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UserEmail relation
 * @method EmailQuery innerJoinUserEmail($relationAlias = null) Adds a INNER JOIN clause to the query using the UserEmail relation
 *
 * @method Email findOne(PropelPDO $con = null) Return the first Email matching the query
 * @method Email findOneOrCreate(PropelPDO $con = null) Return the first Email matching the query, or a new Email object populated from the query conditions when no match is found
 *
 * @method Email findOneByPrimary(boolean $primary) Return the first Email filtered by the primary column
 * @method Email findOneByEmail(string $email) Return the first Email filtered by the email column
 * @method Email findOneByDescription(string $description) Return the first Email filtered by the description column
 * @method Email findOneByCreatedAt(string $created_at) Return the first Email filtered by the created_at column
 * @method Email findOneByUpdatedAt(string $updated_at) Return the first Email filtered by the updated_at column
 *
 * @method array findById(int $id) Return Email objects filtered by the id column
 * @method array findByPrimary(boolean $primary) Return Email objects filtered by the primary column
 * @method array findByEmail(string $email) Return Email objects filtered by the email column
 * @method array findByDescription(string $description) Return Email objects filtered by the description column
 * @method array findByCreatedAt(string $created_at) Return Email objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return Email objects filtered by the updated_at column
 */
abstract class BaseEmailQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseEmailQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = null, $modelName = null, $modelAlias = null)
    {
        if (null === $dbName) {
            $dbName = 'default';
        }
        if (null === $modelName) {
            $modelName = 'UserBundle\\Model\\Email';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new EmailQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   EmailQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return EmailQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof EmailQuery) {
            return $criteria;
        }
        $query = new EmailQuery(null, null, $modelAlias);

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
     * @param     PropelPDO $con an optional connection object
     *
     * @return   Email|Email[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = EmailPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(EmailPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 Email A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneById($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 Email A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `primary`, `email`, `description`, `created_at`, `updated_at` FROM `email` WHERE `id` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new Email();
            $obj->hydrate($row);
            EmailPeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return Email|Email[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|Email[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return EmailQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(EmailPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return EmailQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(EmailPeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id >= 12
     * $query->filterById(array('max' => 12)); // WHERE id <= 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmailQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(EmailPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(EmailPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmailPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the primary column
     *
     * Example usage:
     * <code>
     * $query->filterByPrimary(true); // WHERE primary = true
     * $query->filterByPrimary('yes'); // WHERE primary = true
     * </code>
     *
     * @param     boolean|string $primary The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmailQuery The current query, for fluid interface
     */
    public function filterByPrimary($primary = null, $comparison = null)
    {
        if (is_string($primary)) {
            $primary = in_array(strtolower($primary), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(EmailPeer::PRIMARY, $primary, $comparison);
    }

    /**
     * Filter the query on the email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE email = 'fooValue'
     * $query->filterByEmail('%fooValue%'); // WHERE email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $email The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmailQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $email)) {
                $email = str_replace('*', '%', $email);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(EmailPeer::EMAIL, $email, $comparison);
    }

    /**
     * Filter the query on the description column
     *
     * Example usage:
     * <code>
     * $query->filterByDescription('fooValue');   // WHERE description = 'fooValue'
     * $query->filterByDescription('%fooValue%'); // WHERE description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $description The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmailQuery The current query, for fluid interface
     */
    public function filterByDescription($description = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $description)) {
                $description = str_replace('*', '%', $description);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(EmailPeer::DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmailQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(EmailPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(EmailPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmailPeer::CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return EmailQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(EmailPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(EmailPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EmailPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related CompanyEmail object
     *
     * @param   CompanyEmail|PropelObjectCollection $companyEmail  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 EmailQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCompanyEmail($companyEmail, $comparison = null)
    {
        if ($companyEmail instanceof CompanyEmail) {
            return $this
                ->addUsingAlias(EmailPeer::ID, $companyEmail->getEmailId(), $comparison);
        } elseif ($companyEmail instanceof PropelObjectCollection) {
            return $this
                ->useCompanyEmailQuery()
                ->filterByPrimaryKeys($companyEmail->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCompanyEmail() only accepts arguments of type CompanyEmail or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CompanyEmail relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return EmailQuery The current query, for fluid interface
     */
    public function joinCompanyEmail($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CompanyEmail');

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
            $this->addJoinObject($join, 'CompanyEmail');
        }

        return $this;
    }

    /**
     * Use the CompanyEmail relation CompanyEmail object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \CompanyBundle\Model\CompanyEmailQuery A secondary query class using the current class as primary query
     */
    public function useCompanyEmailQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCompanyEmail($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CompanyEmail', '\CompanyBundle\Model\CompanyEmailQuery');
    }

    /**
     * Filter the query by a related StoreEmail object
     *
     * @param   StoreEmail|PropelObjectCollection $storeEmail  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 EmailQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByStoreEmail($storeEmail, $comparison = null)
    {
        if ($storeEmail instanceof StoreEmail) {
            return $this
                ->addUsingAlias(EmailPeer::ID, $storeEmail->getEmailId(), $comparison);
        } elseif ($storeEmail instanceof PropelObjectCollection) {
            return $this
                ->useStoreEmailQuery()
                ->filterByPrimaryKeys($storeEmail->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByStoreEmail() only accepts arguments of type StoreEmail or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the StoreEmail relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return EmailQuery The current query, for fluid interface
     */
    public function joinStoreEmail($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('StoreEmail');

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
            $this->addJoinObject($join, 'StoreEmail');
        }

        return $this;
    }

    /**
     * Use the StoreEmail relation StoreEmail object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \StoreBundle\Model\StoreEmailQuery A secondary query class using the current class as primary query
     */
    public function useStoreEmailQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinStoreEmail($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'StoreEmail', '\StoreBundle\Model\StoreEmailQuery');
    }

    /**
     * Filter the query by a related UserEmail object
     *
     * @param   UserEmail|PropelObjectCollection $userEmail  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 EmailQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByUserEmail($userEmail, $comparison = null)
    {
        if ($userEmail instanceof UserEmail) {
            return $this
                ->addUsingAlias(EmailPeer::ID, $userEmail->getEmailId(), $comparison);
        } elseif ($userEmail instanceof PropelObjectCollection) {
            return $this
                ->useUserEmailQuery()
                ->filterByPrimaryKeys($userEmail->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUserEmail() only accepts arguments of type UserEmail or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UserEmail relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return EmailQuery The current query, for fluid interface
     */
    public function joinUserEmail($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UserEmail');

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
            $this->addJoinObject($join, 'UserEmail');
        }

        return $this;
    }

    /**
     * Use the UserEmail relation UserEmail object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \UserBundle\Model\UserEmailQuery A secondary query class using the current class as primary query
     */
    public function useUserEmailQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUserEmail($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UserEmail', '\UserBundle\Model\UserEmailQuery');
    }

    /**
     * Filter the query by a related Company object
     * using the company_email table as cross reference
     *
     * @param   Company $company the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   EmailQuery The current query, for fluid interface
     */
    public function filterByCompany($company, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useCompanyEmailQuery()
            ->filterByCompany($company, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related Store object
     * using the store_email table as cross reference
     *
     * @param   Store $store the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   EmailQuery The current query, for fluid interface
     */
    public function filterByStore($store, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useStoreEmailQuery()
            ->filterByStore($store, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related User object
     * using the user_email table as cross reference
     *
     * @param   User $user the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   EmailQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useUserEmailQuery()
            ->filterByUser($user, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   Email $email Object to remove from the list of results
     *
     * @return EmailQuery The current query, for fluid interface
     */
    public function prune($email = null)
    {
        if ($email) {
            $this->addUsingAlias(EmailPeer::ID, $email->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     EmailQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(EmailPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     EmailQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(EmailPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     EmailQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(EmailPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     EmailQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(EmailPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     EmailQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(EmailPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     EmailQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(EmailPeer::CREATED_AT);
    }
}
