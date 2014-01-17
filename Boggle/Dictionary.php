<?
    namespace Boggle;

    interface Dictionary {

        /** @const int MISSING_PREFIX       Used to indicate that a given string does not exist in the dictionary */
        const MISSING_PREFIX = 9999;

        /**
         * Get a list of all possible words that contain only the letters provided
         *
         * @abstract
         *
         * @param string $letters
         *
         * @return string[]
         */
        function getWords($letters);


        /**
         * Get the length of the longest word in the dictionary
         *
         * @abstract
         *
         * @return int
         */
        public function getLongestWordLength();
    }