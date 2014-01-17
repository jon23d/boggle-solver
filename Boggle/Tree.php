<?
    namespace Boggle;

    class Tree {

        const END_OF_WORD_MARKER = 'end-of-word';

        /** @var array $tree      The nodes comprising this Tree */
        private $tree = array();

        /**
         * Add a word to the Tree
         *
         * @param string $word
         *
         * @return Tree
         */
        public function addWord($word) {
            // make an array of the letters
            $letters = str_split($word);

            // A reference to the current level in the tree
            $node =& $this->tree;

            for ($i = 0; $i < count($letters); $i++) {
                $letter = $letters[$i];

                // if this letter doesn't exist within the current node, create an array
                if (!isset($node[$letter])) {
                    $node[$letter] = array();
                }

                // Update the reference to the current level with the next node
                $node =& $node[$letter];
            }

            // mark this node as being a word-ending node by adding a marker to the array
            $node[self::END_OF_WORD_MARKER] = true;
        }


        /**
         * Does the provided word exist in the tree?
         *
         * @param string $word
         *
         * @return bool
         */
        public function doesWordExist($word) {
            $letters = str_split($word);
            $node =& $this->tree;

            for ($i = 0; $i < strlen($word); $i++) {
                // if the next node doesn't exist, then neither does the word
                if (!isset($node[$letters[$i]])) {
                    return false;
                }

                $node =& $node[$letters[$i]];
            }

            // If the final node contains the end of word marker, then we have a word
            return (isset($node[self::END_OF_WORD_MARKER])) ? true : false;
        }
    }