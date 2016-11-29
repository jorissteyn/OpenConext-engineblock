<?php

namespace OpenConext\EngineBlock\Authentication\Value;

use OpenConext\EngineBlock\Assert\Assertion;

final class CollabPersonId
{
    /**
     * Required namespace prefix
     */
    const URN_NAMESPACE = 'urn:collab:person';

    /**
     * Max length of the CollabPersonId.
     */
    const MAX_LENGTH = 255;

    /**
     * @var string
     */
    private $collabPersonId;

    /**
     * @param Uid                   $uid
     * @param SchacHomeOrganization $schacHomeOrganization
     * @return CollabPersonId
     */
    public static function generateFrom(Uid $uid, SchacHomeOrganization $schacHomeOrganization)
    {
        $collabPersonId = implode(
            ':',
            [self::URN_NAMESPACE, $schacHomeOrganization->getSchacHomeOrganization(), $uid->getUid()]
        );

        return new self($collabPersonId);
    }

    /**
     * This method solely exists for compatibility between EB versions and existing SPs.
     * Replacing the @-sign for underscores in collabPersonIds was part of the LDAP module.
     *
     * See: https://www.pivotaltracker.com/story/show/134915765 and
     * https://github.com/OpenConext/OpenConext-engineblock/commit/e6631acd4c4299329c5c34899de2f3a464975a5a
     *
     * @param Uid $uid
     * @param SchacHomeOrganization $schacHomeOrganization
     * @return CollabPersonId
     */
    public static function generateWithReplacedAtSignFrom(Uid $uid, SchacHomeOrganization $schacHomeOrganization)
    {
        $collabPersonId = implode(
            ':',
            [
                self::URN_NAMESPACE,
                $schacHomeOrganization->getSchacHomeOrganization(),
                str_replace('@', '_', $uid->getUid()),
            ]
        );

        return new self($collabPersonId);
    }

    /**
     * @param string $collabPersonId
     */
    public function __construct($collabPersonId)
    {
        Assertion::nonEmptyString($collabPersonId, 'collabPersonId');
        Assertion::startsWith(
            $collabPersonId,
            self::URN_NAMESPACE,
            sprintf('a CollabPersonId must start with the "%s" namespace', self::URN_NAMESPACE)
        );
        Assertion::maxLength(
            $collabPersonId,
            self::MAX_LENGTH,
            sprintf('CollabPersonId length may not exceed %d characters', self::MAX_LENGTH)
        );

        $this->collabPersonId = $collabPersonId;
    }

    /**
     * @return string
     */
    public function getCollabPersonId()
    {
        return $this->collabPersonId;
    }

    /**
     * @param CollabPersonId $other
     * @return bool
     */
    public function equals(CollabPersonId $other)
    {
        return $this->collabPersonId === $other->collabPersonId;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('CollabPersonId(%s)', $this->collabPersonId);
    }
}
