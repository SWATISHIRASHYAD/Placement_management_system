<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Placement Management System</title>
    <style>
        /* General Page Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .faq-container {
            width: 70%;
            max-width: 800px;
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Header Styling */
        h2 {
            text-align: center;
            color: #0056b3;
            margin-bottom: 20px;
            font-size: 28px;
            border-bottom: 2px solid #0056b3;
            padding-bottom: 10px;
        }

        /* FAQ Item Styling */
        .faq-item {
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            margin: 15px 0;
            padding: 15px 20px;
            background-color: #fafafa;
            transition: background-color 0.3s, box-shadow 0.3s;
        }

        .faq-item:hover {
            background-color: #f0f8ff;
            box-shadow: 0 2px 10px rgba(0, 86, 179, 0.1);
        }

        /* FAQ Question Styling */
        .faq-question {
            font-weight: bold;
            font-size: 18px;
            color: #333;
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        .faq-question::before {
            content: "+";
            margin-right: 10px;
            font-size: 20px;
            color: #0056b3;
            transition: transform 0.2s;
        }

        /* Rotates icon when answer is visible */
        .faq-answer.active + .faq-question::before {
            transform: rotate(45deg);
            color: #ff3333;
        }

        /* FAQ Answer Styling */
        .faq-answer {
            font-size: 16px;
            color: #555;
            line-height: 1.5;
            display: none; /* Initially hidden */
            margin-top: 10px;
        }
    </style>
    <script>
        function toggleAnswer(id) {
            var answer = document.getElementById(id);
            if (answer.style.display === 'none') {
                answer.style.display = 'block';
            } else {
                answer.style.display = 'none';
            }
        }
    </script>
</head>
<body>

<div class="faq-container">
    <h2>Frequently Asked Questions</h2>
    
    <?php
    // Sample FAQ array
    $faqs = [
        [
            "question" => "What is the placement process?",
            "answer" => "The placement process involves multiple steps including eligibility checks, written tests, group discussions, and interviews."
        ],
        [
            "question" => "Which companies visit for placements?",
            "answer" => "Companies from various sectors visit our campus, including tech, finance, consulting, and more."
        ],
        [
            "question" => "How can I apply for placements?",
            "answer" => "Students can apply for placements through the college's placement portal, which requires registration and profile completion."
        ],
        [
            "question" => "What is the eligibility criteria for placement?",
            "answer" => "The eligibility criteria vary by company but generally include minimum percentage requirements and specific skill sets."
        ],
        [
            "question" => "Are there any placement preparation workshops?",
            "answer" => "Yes, the college organizes several workshops on resume writing, interview skills, and aptitude tests to help students prepare."
        ],
        [
            "question" => "What should I include in my resume?",
            "answer" => "Your resume should include your educational background, skills, internships, projects, and extracurricular activities."
        ],
        [
            "question" => "How can I improve my interview skills?",
            "answer" => "You can improve your interview skills by practicing common interview questions, attending mock interviews, and seeking feedback."
        ],
        [
            "question" => "What types of jobs are available for fresh graduates?",
            "answer" => "Fresh graduates can find opportunities in software development, data analysis, marketing, finance, and various other fields."
        ],
        [
            "question" => "Is there any support for students after placements?",
            "answer" => "Yes, many colleges provide ongoing support and resources for students to ensure a smooth transition into their new roles."
        ]
    ];

    // Display each FAQ
    foreach ($faqs as $index => $faq) {
        echo "<div class='faq-item'>";
        echo "<div class='faq-question' onclick='toggleAnswer(\"answer$index\")'>" . $faq['question'] . "</div>";
        echo "<div class='faq-answer' id='answer$index'>" . $faq['answer'] . "</div>";
        echo "</div>";
    }
    ?>
</div>

</body>
</html>
