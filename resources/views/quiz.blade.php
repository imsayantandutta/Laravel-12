<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $product->name }} Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card shadow p-4">
        <h3 class="text-primary">{{ $product->name }}</h3>
        <p class="text-muted mb-4">Question {{ $questionIndex + 1 }} of {{ count($product->questions) }}</p>

        <form action="{{ route('quiz.store', [$product->id, $questionIndex]) }}" method="POST">
            @csrf
            <input type="hidden" name="question_id" value="{{ $question->id }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <h5>{{ $question->question_text }}</h5>
            <div class="mt-3">
                @foreach ($question->answers as $answer)
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="answer_id" id="answer{{ $answer->id }}" value="{{ $answer->id }}" required>
                        <label class="form-check-label" for="answer{{ $answer->id }}">
                            {{ $answer->answer_text }}
                        </label>
                    </div>
                @endforeach
            </div>

            <button type="submit" class="btn btn-primary mt-3">
                {{ $questionIndex + 1 == count($product->questions) ? 'Submit' : 'Next' }}
            </button>
        </form>
    </div>
</div>
</body>
</html>