// Eng Wei Jiun

// This class is use to store the position of piece.

// This class is part of the Model in the MVC design pattern.
// Explanation: The model is the core functional part of the program.
// They encapsulate the game's data and the logic of how the game is played.

package Model;

public class Position {
    private int x;
    private int y;

    public Position(int x, int y) {
        this.x = x;
        this.y = y;
    }

    public int getX() {
        return x;
    }

    public int getY() {
        return y;
    }

}
