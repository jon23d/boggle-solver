<?
    /**
     * A Dictionary that uses a file word list
     *
     * @package Boggle
     * @author Jonathon Deason <jon23d@gmail.com>
     */
    namespace Boggle\Dictionaries;

    class TextFileDictionary implements \Boggle\Dictionary {

        /** @var string[]       A list of valid words using the letters provided in the constructor */
        private $word_list = array();

        /** @var bool $is_initialized       An initialization flag */
        private $is_initialized = false;

        /** @var int $largest_word_length   The length of the longest word */
        private $largest_word_length = 0;

        /** @var string $file_name          The name of the file that contains the word list */
        private $file_name = null;

        /** @var string $delimiter          The delimiter used in the file */
        private $delimiter = null;


        /**
         * Create a new TextFileDictionary, using the provided file and exploding with the given delimiter
         *
         * @throws \InvalidArgumentException
         *
         * @param string $file_name
         * @param string $delimiter
         *
         * @return TextFileDictionary
         */
        public function __construct($file_name, $delimiter) {
            if (!file_exists($file_name)) {
                throw new \InvalidArgumentException('File does not exist: ' . $file_name);
            }

            $this->file_name = $file_name;
            $this->delimiter = $delimiter;
        }


        /**
         * Get a list of all possible words
         *
         * @throws \DomainException
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
            $all_words = $this->loadWords();

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

            if (!count($this->word_list)) {
                throw new \DomainException('Word list is empty');
            }

            // set the initialization flag and return the word list
            $this->is_initialized = true;

            return $this->word_list;
        }


        /**
         * Get the list of test words from test_dictionary_word_list.txt
         *
         * @throws \InvalidArgumentException
         *
         * @return string[]
         */
        private function loadWords() {
            $words = explode($this->delimiter, file_get_contents($this->file_name));

            if (!count($words)) {
                throw new \InvalidArgumentException('File does not contain words when using provided delimiter');
            }

            return $words;
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