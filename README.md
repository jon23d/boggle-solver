boggle-solver
=============

Going further:
 - External resources -- a word list, dictionary, or API that provides words would be helpful.  I created
   a dictionary interface to allow others to easily add other data sources
 - Performance -- I used recursion to solve this problem, and noticed quite quickly that it was helpful to
   limit the possibilities by the early-on removal of combinations that couldn't exist due to limiting factors
   such as word length, or the absence of a 'prefix' in the tree.  I also chose to make nodes in the tree simple
   to keep the object count down
 - Key data structures -- A class that implements the Dictionary interface.  The tree class uses the Dictionary
   class to load words into a tree structure for rapid searching.  The Game class represents the boggle board,
   and uses the Tile class for easy traversal of the board.
 - Words within words -- Yes, it does :)