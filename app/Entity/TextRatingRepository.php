<?php namespace App\Entity;

use Doctrine\ORM\NoResultException;

/**
 *
 */
class TextRatingRepository extends EntityRepository {
	static public $ratings = array(
		6 => '6 — Шедьовър',
		5 => '5 — Много добро',
		4 => '4 — Добро',
		3 => '3 — Посредствено',
		2 => '2 — Лошо',
		1 => '1 — Отвратително',
	);

	/**
	 * Get user rating for a given text.
	 * Return new Rating object if none exists.
	 * @param Text $text
	 * @param User $user
	 * @return TextRating
	 */
	public function getByTextAndUser(Text $text, User $user) {
		$dql = "SELECT r FROM {$this->getEntityName()} r WHERE r.text = {$text->getId()} AND r.user = {$user->getId()}";
		try {
			return $this->_em->createQuery($dql)->setMaxResults(1)->getSingleResult();
		} catch (NoResultException $e) {
			return new TextRating($text, $user);
		}
	}

	/**
	 * Get all ratings for a given text.
	 * @param Text $text
	 * @return array Ratings with users who gave them
	 */
	public function getByText(Text $text) {
		return $this->getQueryBuilder()
			->select('e', 'u')
			->leftJoin('e.user', 'u')
			->where('e.text = ?1')->setParameter(1, $text)
			->orderBy('e.date', 'desc')
			->getQuery()->getArrayResult();
	}

	/**
	 * Get all ratings of a given user.
	 * @param User $user
	 * @return array All user ratings
	 */
	public function getByUser(User $user) {
		return $this->getQueryBuilder()
			->select('e', 't')
			->leftJoin('e.text', 't')
			->where('e.user = ?1')->setParameter(1, $user)
			->orderBy('e.date', 'desc')
			->getQuery()->getArrayResult();
	}
}
