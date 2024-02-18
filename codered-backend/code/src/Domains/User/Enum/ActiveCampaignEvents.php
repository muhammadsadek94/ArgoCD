<?php


namespace App\Domains\User\Enum;


use App\Foundation\BasicEnum;

class ActiveCampaignEvents extends BasicEnum
{
    const LESSON_COMPLETED = 'lesson_completed';
    const LESSON_COMPLETED_IN_LEARN_PATH = 'lesson_completed_in_learn_path';
    const ENROLL_COURSE = 'course_enroll';
    const COURSE_COMPLETED = 'course_completed';
    const COURSE_COMPLETED_IN_LEARNPATH = 'course_completed_in_learn_path';
    const ASSESSMENT_FAIL = 'assessment_fail';
    const ASSESSMENT_PASSED = 'assessment_passed';
    const CHAPTER_COMPLETED = 'complete_chapter';
    const FIRST_CHAPTER_COMPLETED = 'first_chapter_completed';
    const CERTIFICATE_GENERATED = 'certificate_generated';
    const CONTACT_US = 'contact_us';
    const ARTICLE_DOWNLOAD_ATTACHMENT = 'download_attachment';
    const REQUEST_DOWNLOAD_COURSE_SYLLABUS = 'download_syllabus';
}
