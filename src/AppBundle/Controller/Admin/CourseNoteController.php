<?php
namespace AppBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Topxia\Common\ArrayToolkit;
use Topxia\Common\Paginator;
use Topxia\Service\Course\CourseService;
use Topxia\Service\Course\NoteService;

class CourseNoteController extends BaseController
{
	public function indexAction(Request $request)
	{
		$conditions = $request->query->all();
        
        if ( isset($conditions['keywordType']) && $conditions['keywordType'] == 'courseTitle'){
            $courses = $this->getCourseService()->findCoursesByLikeTitle(trim($conditions['keyword']));
            $conditions['courseIds'] = ArrayToolkit::column($courses, 'id'); 
            if (count($conditions['courseIds']) == 0){
                return $this->render('CourseNote:index.html.twig', array(
                    'notes' => array(),
                    'paginator' => new Paginator($request,0,20),
                    'users'=> array(),
                    'lessons'=> array(),
                    'courses'=>array()
                ));
            }  
        }        

        $paginator = new Paginator(
            $request,
            $this->getNoteService()->countCourseNotes($conditions),
            20
        );
        $notes = $this->getNoteService()->searchNotes(
            $conditions,
            array('createdTime' => 'DESC'),
            $paginator->getOffsetCount(),
            $paginator->getPerPageCount()
        );
        $users = $this->getUserService()->findUsersByIds(ArrayToolkit::column($notes, 'userId'));
        $courses = $this->getCourseService()->findCoursesByIds(ArrayToolkit::column($notes, 'courseId'));
        $lessons = $this->getCourseService()->findLessonsByIds(ArrayToolkit::column($notes, 'lessonId'));
		return $this->render('CourseNote:index.html.twig',array(
            'notes' => $notes,
            'paginator' => $paginator,
            'users'=>$users,
            'lessons'=>$lessons,
            'courses'=>$courses
		));
	}

    public function deleteAction(Request $request, $id)
    {
        $note = $this->getNoteService()->deleteNote($id);

        return $this->createJsonResponse(true);
    }

    public function batchDeleteAction(Request $request)
    {
        $ids = $request->request->get('ids', array());
        $this->getNoteService()->deleteNotes($ids);

        return $this->createJsonResponse(true);
    }

    /**
     * @return NoteService
     */
    protected function getNoteService()
    {
        return $this->getServiceKernel()->createService('Course:NoteService');
    }

    /**
     * @return CourseService
     */
    protected function getCourseService()
    {
    	return $this->getServiceKernel()->createService('Course:CourseService');
    }
}