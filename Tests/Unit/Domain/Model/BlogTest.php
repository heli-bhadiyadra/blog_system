<?php

declare(strict_types=1);

namespace NITSAN\NsBlogSystem\Tests\Unit\Domain\Model;

use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 */
class BlogTest extends UnitTestCase
{
    /**
     * @var \NITSAN\NsBlogSystem\Domain\Model\Blog|MockObject|AccessibleObjectInterface
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = $this->getAccessibleMock(
            \NITSAN\NsBlogSystem\Domain\Model\Blog::class,
            ['dummy']
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getTitleReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getTitle()
        );
    }

    /**
     * @test
     */
    public function setTitleForStringSetsTitle(): void
    {
        $this->subject->setTitle('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('title'));
    }

    /**
     * @test
     */
    public function getDescriptionReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getDescription()
        );
    }

    /**
     * @test
     */
    public function setDescriptionForStringSetsDescription(): void
    {
        $this->subject->setDescription('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('description'));
    }

    /**
     * @test
     */
    public function getImagesReturnsInitialValueForFileReference(): void
    {
        self::assertEquals(
            null,
            $this->subject->getImages()
        );
    }

    /**
     * @test
     */
    public function setImagesForFileReferenceSetsImages(): void
    {
        $fileReferenceFixture = new \TYPO3\CMS\Extbase\Domain\Model\FileReference();
        $this->subject->setImages($fileReferenceFixture);

        self::assertEquals($fileReferenceFixture, $this->subject->_get('images'));
    }

    /**
     * @test
     */
    public function getCreateddateReturnsInitialValueForDateTime(): void
    {
        self::assertEquals(
            null,
            $this->subject->getCreateddate()
        );
    }

    /**
     * @test
     */
    public function setCreateddateForDateTimeSetsCreateddate(): void
    {
        $dateTimeFixture = new \DateTime();
        $this->subject->setCreateddate($dateTimeFixture);

        self::assertEquals($dateTimeFixture, $this->subject->_get('createddate'));
    }
}
