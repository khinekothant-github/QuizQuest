<template>
    <Navbar></Navbar>
  <section
    class="bg-gray-50 dark:bg-gray-900 flex items-center justify-center overflow-y-auto"
  >
    <div
      class="max-w-7xl mx-auto sm:px-6 lg:px-8  "
      style="padding-top: 10%; padding-bottom: 5%"
    >
      <div
        class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg"
      >
        <div class="p-4 text-gray-900 dark:text-gray-100">
          <!-- Display loading state -->
          <div v-if="loading" class="text-center w-full">
            <p>Loading questions...</p>
            <div class="loader"></div> <!-- You can replace this with a spinner component -->
          </div>

          <!-- Display quiz questions before submission -->
          <div v-else-if="!results">
            <form @submit.prevent="submitQuiz">
              <div
                v-for="(question, index) in questions"
                :key="index"
                class="mb-4"
              >
                <p
                  class="block mb-1 text-sm font-medium text-gray-900 dark:text-white"
                >
                  {{ question.question }}
                </p>
                <div
                  v-for="(answer, answerIndex) in getShuffledAnswers(index)"
                  :key="answerIndex"
                >
                  <label
                    class="flex items-center space-x-2 mt-1 p-1 bg-gray-100 dark:bg-gray-700 rounded-lg shadow-sm"
                  >
                    <input
                      type="radio"
                      :name="'question_' + index"
                      :value="answer"
                      v-model="userAnswers[index]"
                      class="form-radio h-4 w-4 text-indigo-600"
                    />
                    <span
                      class="text-sm font-medium text-gray-900 dark:text-white"
                    >
                      {{ answer }}
                    </span>
                  </label>
                </div>
              </div>

              <button
                type="submit"
                class="w-full mt-6 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300"
              >
                Submit
              </button>
            </form>
          </div>

          <!-- Display results after submission -->
          <div v-if="results">
            <h2
              class="block mb-1 text-sm font-medium text-gray-900 dark:text-white"
            >
              Your Score: {{ score }} / {{ results.length }}
            </h2>
            <div v-for="(result, index) in results" :key="index" class="mb-4">
              <p
                class="block mb-1 text-sm font-medium text-gray-900 dark:text-white"
              >
                {{ result.question }}
              </p>
              <div
                v-for="(answer, answerIndex) in result.answers"
                :key="answerIndex"
                class="flex items-center mt-1"
              >
                <input
                  type="radio"
                  disabled
                  :checked="answer === result.user_answer"
                  class="form-radio h-4 w-4 text-indigo-600"
                />
                <span
                  class="text-sm font-medium"
                  :class="{
                    'text-green-600 dark:text-green-400':
                      answer === result.correct_answer,
                    'text-red-600 dark:text-red-400':
                      answer === result.user_answer &&
                      answer !== result.correct_answer,
                    'text-gray-700 dark:text-gray-300':
                      answer !== result.correct_answer &&
                      answer !== result.user_answer,
                  }"
                >
                  {{ answer }}
                </span>
                <span
                  v-if="answer === result.correct_answer"
                  class="ml-2 text-green-600 dark:text-green-400 font-medium"
                >
                  (Correct Answer)
                </span>
                <span
                  v-else-if="
                    answer === result.user_answer &&
                    answer !== result.correct_answer
                  "
                  class="ml-2 text-red-600 dark:text-red-400 font-medium"
                >
                  (Your Answer)
                </span>
              </div>
            </div>

            <button
              @click="loadNewQuizzes"
              class="w-full mt-6 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300"
            >
              Load New Quizzes
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

  <script>
import Navbar from "../components/Navbar.vue";
import QuizService from "../services/quiz"; // Import the QuizService

export default {

  name: "Quiz",
  components:{
    Navbar
  },
  data() {
    return {
      questions: [],
      userAnswers: [],
      results: null,
      score: 0,
      shuffledAnswersList: [], // Store shuffled answers
      loading: true, // Add loading state
    };
  },
  methods: {
    // Fetch quizzes from API
    async fetchQuizzes() {
      this.loading = true; // Set loading to true
      try {
        const response = await QuizService.fetchQuizzes(); // Use QuizService to fetch quizzes
        this.questions = response.data.questions;
        this.userAnswers = new Array(this.questions.length).fill(null);
        this.shuffleAnswers(); // Shuffle answers once after fetching questions
      } catch (error) {
        console.error("Failed to fetch quizzes:", error);
      } finally {
        this.loading = false; // Set loading to false after fetching
      }
    },

    // Shuffle answers to randomize them
    shuffleAnswers() {
      this.shuffledAnswersList = this.questions.map((question) => {
        const answers = [
          ...question.incorrect_answers,
          question.correct_answer,
        ];
        return answers.sort(() => Math.random() - 0.5); // Shuffle answers
      });
    },

    // Get shuffled answers for a specific question
    getShuffledAnswers(index) {
      return this.shuffledAnswersList[index]; // Return the pre-shuffled answers
    },

    // Submit quiz answers to the API
    async submitQuiz() {
      try {
        const response = await QuizService.submitQuiz(
          this.questions,
          this.userAnswers
        ); // Use QuizService to submit quiz
        this.results = response.data.results;
        this.score = response.data.score;
      } catch (error) {
        console.error("Failed to submit quiz:", error);
      }
    },

    // Load new quizzes
    async loadNewQuizzes() {
      this.results = null;
      await this.fetchQuizzes();
    },
  },
  mounted() {
    this.fetchQuizzes(); // Fetch quizzes when component is mounted
  },
};
</script>

  <style scoped>
.loader {
  border: 4px solid rgba(0, 0, 0, 0.1);
  border-radius: 50%;
  border-top: 4px solid #3498db;
  width: 40px;
  height: 40px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
