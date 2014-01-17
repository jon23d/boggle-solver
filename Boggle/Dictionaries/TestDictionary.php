<?
    namespace Boggle\Dictionaries;

    class TestDictionary implements \Boggle\Dictionary {

        /** @var string[]       A list of valid words using the letters provided in the constructor */
        private $word_list = array();

        /** @var bool $is_initialized       An initialization flag */
        private $is_initialized = false;

        /** @var int $largest_word_length   The length of the longest word */
        private $largest_word_length = 0;


        /**
         * Get a list of all possible words
         *
         * @param string $letters
         *
         * @return string[]
         */
        function getWords($letters) {
            // to avoid re-initialization...
            if (true === $this->is_initialized) {
                return $this->word_list;
            }

            // all words in this dictionary
            $all_words = $this->getTestWords();

            // We are going to filter all of the words in the dictionary first to ensure that the
            // list is as small as possible.  Only use words that can be formed by just the provided
            // letters.  We are also going to determine the longest word length here.
            $regex = '/^[' . $letters . ']+$/';
            foreach ($all_words as $word) {
                if (1 === preg_match($regex, strtolower($word))) {
                    $this->word_list[] = strtolower($word);
                    if ($this->largest_word_length < strlen($word)) {
                        $this->largest_word_length = strlen($word);
                    }
                }
            }

            // set the initialization flag and return the word list
            $this->is_initialized = true;
            return $this->word_list;
        }


        /**
         * Get the list of test words from test_dictionary_word_list.txt
         *
         * @return string[]
         */
        private function getTestWords() {
            return explode(' ', file_get_contents(__DIR__ . '/test_dictionary_word_list.txt'));
        }


        /**
         * Get the length of the longest word in the dictionary
         *
         * @return int
         */
        public function getLongestWordLength() {
            return $this->largest_word_length;
        }
    }