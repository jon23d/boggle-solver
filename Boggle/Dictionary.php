<?
    namespace Boggle;

    interface Dictionary {


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
    }