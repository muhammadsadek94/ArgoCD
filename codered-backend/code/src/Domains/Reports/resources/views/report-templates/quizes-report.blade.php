<table>
    <thead>
        <tr>
            <td>ID</td>
            <td>course_id</td>
            <td>lesson_id</td>
            <td>Course Name</td>
            <td>Lesson Name</td>
            <td>question</td>
            <td>description</td>
            <td>Answer 1</td>
            <td>Answer 2</td>
            <td>Answer 3</td>
            <td>Answer 4</td>
            <td>Answer 5</td>
            <td>Correct Answer</td>
        </tr>
    </thead>

    <tbody>
    @foreach($quizes as $quiz)
        <tr>
            @php
                $answers_array = [
                    'answer_1' => '',
                    'answer_2' => '',
                    'answer_3' => '',
                    'answer_4' => '',
                    'answer_5' => '',
                ];
                $correct_answer = '';
                $answers_quiz = is_array($quiz->answers) ? $quiz->answers : [];
                $count = 0;
                foreach ($answers_quiz as $iteration => $quiz_answer) {

                    $iteration = $iteration+1;

                    if($iteration > 5) {
                        break;
                    }
                    $answers_array["answer_{$iteration}"] = $quiz_answer['text'];
                    if ($quiz_answer['is_correct']) {
                        $correct_answer = $quiz_answer['text'];
                    }
                    $count ++;
                }

            @endphp
            <td>{{$quiz->id}}</td>
            <td>{{$quiz->course_id}}</td>
            <td>{{$quiz->lesson_id}}</td>
            <td>{{$quiz->lesson->course->name}}</td>
            <td>{{$quiz->lesson->name}}</td>
            <td>{{strip_tags($quiz->question)}}</td>
            <td>{{strip_tags($quiz->description)}}</td>
            <td>{{$answers_array['answer_1']}}</td>
            <td>{{$answers_array['answer_2']}}</td>
            <td>{{$answers_array['answer_3']}}</td>
            <td>{{$answers_array['answer_4']}}</td>
            <td>{{$answers_array['answer_5']}}</td>
            <td>{{$correct_answer}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
